<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\UserDepartment;
use App\Models\Governements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض صفحة إعدادات الحساب (القسم والمدينة)
     */
    public function accountSettings()
    {
        $user = Auth::user();
        $departments = Department::with(['sub_departments' => function($q) {
            $q->where('status', 1);
        }])->where('department_id', 0)->where('status', 1)->get();

        $governorates = Governements::all();

        // الحصول على الأقسام المختارة للمستخدم
        $userDepartments = $user->userDepartments()->with('commentable')->get();

        // الحصول على المدن المختارة للمستخدم (للمزودين)
        $selectedCities = collect();
        if ($user->role_id == 3) {
            $selectedCities = $user->providerCities()->with('governement')->get();
        }

        // إضافة متغير اللغة للعرض
        $lang = config('app.locale');

        return view('front_office.settings.account', compact('user', 'departments', 'governorates', 'userDepartments', 'selectedCities', 'lang'));
    }

    /**
     * عرض صفحة إعدادات الحساب (عرض فقط)
     */
    public function showAccountSettings()
    {
        $user = Auth::user();

        // الحصول على الأقسام المختارة للمستخدم
        $userDepartments = $user->userDepartments()->with('commentable')->get();

        // الحصول على المدن المختارة للمستخدم (للمزودين)
        $selectedCities = collect();
        if ($user->role_id == 3) {
            $selectedCities = $user->providerCities()->with('governement')->get();
        }

        // إضافة متغير اللغة للعرض
        $lang = config('app.locale');

        return view('front_office.settings.show_account', compact('user', 'userDepartments', 'selectedCities', 'lang'));
    }

    /**
     * عرض صفحة إعدادات الملف الشخصي
     */
    public function profileSettings()
    {
        $user = Auth::user();
        $lang = config('app.locale');
        return view('front_office.settings.profile', compact('user', 'lang'));
    }

    /**
     * تحديث إعدادات الحساب (القسم والمدينة)
     */
    public function updateAccountSettings(Request $request)
    {
        $user = Auth::user();

        // التحقق من صحة البيانات
        $request->validate([
            'departments' => 'required|array|min:1|max:3',
            'departments.*' => 'string',
            'cities' => 'array',
            'cities.*' => 'exists:governements,id'
        ], [
            'departments.required' => 'يرجى اختيار قسم واحد على الأقل',
            'departments.min' => 'يرجى اختيار قسم واحد على الأقل',
            'departments.max' => 'يمكنك اختيار 3 أقسام فقط',
            'cities.array' => 'يجب أن تكون المدن قائمة',
            'cities.*.exists' => 'إحدى المدن المختارة غير صحيحة'
        ]);

        // تحديث الأقسام
        if ($request->departments) {
            // حذف الأقسام القديمة
            UserDepartment::where('user_id', $user->id)->delete();

            // إضافة الأقسام الجديدة
            foreach ($request->departments as $item) {
                [$type, $id] = explode('-', $item);
                $commentable_type = $type === 'main'
                    ? \App\Models\Department::class
                    : \App\Models\SubDepartment::class;

                UserDepartment::create([
                    'user_id' => $user->id,
                    'commentable_id' => $id,
                    'commentable_type' => $commentable_type,
                ]);
            }
        }

        // تحديث المدن (للمزودين فقط)
        if ($user->role_id == 3) {
            // حذف المدن القديمة
            \App\Models\ProviderCity::where('user_id', $user->id)->delete();

            // إضافة المدن الجديدة
            if ($request->has('cities') && is_array($request->cities)) {
                foreach ($request->cities as $cityId) {
                    \App\Models\ProviderCity::create([
                        'user_id' => $user->id,
                        'governement_id' => $cityId
                    ]);
                }
            }
        }

        return redirect()->route('user.settings.account')->with('success', 'تم تحديث إعدادات الحساب بنجاح');
    }

    /**
     * تحديث إعدادات الملف الشخصي
     */
    public function updateProfileSettings(Request $request)
    {
        $user = Auth::user();

        // التحقق من صحة البيانات
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'about_me' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'الاسم الأخير مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'about_me.max' => 'نبذة عنك يجب أن تكون أقل من 1000 حرف',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'نوع الصورة غير مدعوم',
            'image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت'
        ]);

        $data = $request->except(['image']);

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/users', $imageName);
            $data['image'] = 'users/' . $imageName;

            // حذف الصورة القديمة
            if ($user->image && $user->image !== 'users/user.png') {
                Storage::disk('public')->delete($user->image);
            }
        }

        // تحديث البيانات
        $user->update($data);

        return redirect()->route('user.settings.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
