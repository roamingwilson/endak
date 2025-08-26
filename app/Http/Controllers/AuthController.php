<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Role;
use App\Models\User;
use App\Models\Water;
use App\Models\BigCar;
use App\Models\Family;
use App\Models\Garden;
use App\Models\Worker;
use App\Models\Teacher;
use App\Models\CarWater;
use App\Models\Cleaning;
use App\Models\PublicGe;
use App\Models\Department;
use App\Models\Contracting;
use App\Models\FollowCamera;
use Illuminate\Http\Request;
use App\Models\CounterInsects;
use App\Models\UserDepartment;
use App\Models\PartyPreparation;
use App\Services\Auth\UserServices;
use App\Http\Controllers\Controller;
use App\Models\AirCondition;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\FurnitureTransportation;
use App\Models\Governements;
use App\Models\HeavyEquipment;
use App\Models\SpareParts;
use App\Models\VanTruck;
use Exception;
use Illuminate\Support\Facades\DB;
use Mockery\Expectation;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }
    public function loginPage()
    {
        $previous_url = url()->previous();
        $url = explode('/', $previous_url);
        $end_url =  end($url);
        if (isset($end_url) && $end_url == 'show') {
            $message = 'من فضلك سجل الدخول اولا';
        } else {
            $message = null;
        }
        return view('front_office.auth.login', compact('message'));
    }
    public function registerPage()
    {
        $previous_url = url()->previous();
        $url = explode('/', $previous_url);
        $end_url =  end($url);
        if (isset($end_url) && $end_url == 'show') {
            $message = 'من فضلك سجل الدخول اولا';
        } else {
            $message = null;
        }

        $countries = Country::all();
        $govers = Governements::all(); // Get all cities

        return view('front_office.auth.register', compact('message', 'countries', 'govers'));
    }

    public function showRegistrationForm()
    {
        return view('front_office.auth.register');
    }


    public function login(Request $request)
    {
        // البحث عن المستخدم بالبريد أو الهاتف
        $user = User::where('email', $request['email'])
            ->orWhere('phone', $request['email'])
            ->first();

        if (!$user) {
            return back()->withErrors([
                'loginError' => 'المستخدم غير موجود أو البيانات غير صحيحة.'
            ]);
        }

        Log::info('Login attempt', ['user_id' => $user->id, 'status' => $user->status]);

        // التحقق من كلمة المرور
        if (!Hash::check($request['password'], $user->password)) {
            return back()->withErrors([
                'loginError' => 'كلمة المرور غير صحيحة.'
            ]);
        }

        // تسجيل الدخول
        Auth::login($user);
        if ($user->status !== 'active') {
            // احفظ بيانات المستخدم في الجلسة
            session([
                'register_user_id' => $user->id,
                'selected_role' => $user->role_id,
                'country_code' => $user->countryObj ? $user->countryObj->code : '+966'
            ]);
            // أرسل كود تحقق جديد
            $otpCode = \App\Models\OtpCode::createOtp($user->phone, 'registration', 10);
            // أرسل الكود عبر الواتساب
            $whatsappPhone = (session('country_code', '+966')) . $user->phone;
            $message = "مرحباً {$user->first_name}!\n\nرمز التحقق الخاص بك هو: {$otpCode->code}\n\nاستخدم هذا الرمز لإكمال عملية تفعيل حسابك في Endak.\n\nإذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة.";
            $sender = \App\Models\WhatsappSender::first();
            if ($sender) {
                sendWhatsAppMessage($whatsappPhone, $message, $sender->number, $sender->token, $sender->instance_id);
            }
            // حوله لصفحة تفعيل الهاتف
            return redirect()->route('activatePhone')->with('message', 'يجب عليك تفعيل حسابك عبر رمز التحقق المرسل إلى هاتفك.');
        }
        return redirect()->intended('/');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login-page');
    }
    public function forgotPassword()
    {
        return view('front_office.auth.forgot-password');
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'first_name' => "required|string|max:255",
                'last_name' => "required|string|max:255",
                'country' => "required|exists:countries,id",
                'governement' => "required|exists:governements,id",
                'role' => "required|in:1,3",
                'password' => "required|min:6|confirmed",
                'phone' => [
                    'required',
                    'string',
                    'min:7',
                    'max:15',
                    'unique:users',
                ],
                'email' => 'nullable|email|unique:users',
                'departments' => 'nullable|array',
                'main_departments' => 'nullable|array',
            ], [
                'phone.string' => 'رقم الهاتف غير صحيح',
                'phone.min' => 'رقم الهاتف يجب أن يكون 7 أرقام على الأقل',
                'phone.max' => 'رقم الهاتف يجب أن يكون 15 رقم على الأكثر',
            ]);

            // تنظيف رقم الهاتف
            $phone = $request->phone;
            // إزالة 00 أو + من البداية إذا وجدت
            $phone = preg_replace('/^(\+|00)/', '', $phone);
            // إزالة أي أحرف غير رقمية
            $phone = preg_replace('/[^0-9]/', '', $phone);
            // التحقق من وجود البلد
            if (!$request->country) {
                return response()->json([
                    'success' => false,
                    'errors' => ['country' => ['يرجى اختيار البلد']]
                ], 422);
            }

            // الحصول على رمز البلد
            $country = Country::find($request->country);
            $countryCode = $country ? $country->code : '+966'; // افتراضي للسعودية

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $phone,
                'email' => $request->email,
                'country' => $request->country,
                'governement' => $request->governement,
                'password' => Hash::make($request->password),
                'status' => 'disactive',
                'role_name' => 'pending',
                'role_id' => $request->role,
            ]);

            // ربط الأقسام الرئيسية والفرعية إذا كان مزود خدمة وتم إرسال الأقسام
            if($request->role == 3 && !empty($request->main_departments)) {
                $mainDepartments = is_array($request->main_departments) ? $request->main_departments : [];
                $subDepartments = is_array($request->departments) ? $request->departments : [];

                // الأقسام الرئيسية
                foreach($mainDepartments as $mainId) {
                    if (!empty($mainId)) {
                        UserDepartment::create([
                            'user_id' => $user->id,
                            'commentable_id' => $mainId,
                            'commentable_type' => \App\Models\Department::class,
                        ]);
                    }
                }

                // الأقسام الفرعية
                foreach($subDepartments as $subItem) {
                    // تحليل القيمة sub-X-parent-Y
                    if(preg_match('/^sub-(\d+)-parent-(\d+)$/', $subItem, $matches)) {
                        $subId = $matches[1];
                        UserDepartment::create([
                            'user_id' => $user->id,
                            'commentable_id' => $subId,
                            'commentable_type' => \App\Models\SubDepartment::class,
                        ]);
                    }
                }
            }

            // إنشاء رمز OTP جديد
            $otpCode = OtpCode::createOtp($phone, 'registration', 10);

            // حفظ معلومات إضافية في session
            session([
                'register_user_id' => $user->id,
                'selected_role' => $request->role,
                'country_code' => $countryCode
            ]);

            // إرسال OTP عبر الواتساب
            $whatsappPhone = $countryCode . $phone;
            $message = "مرحباً {$request->first_name}!\n\nرمز التحقق الخاص بك هو: {$otpCode->code}\n\nاستخدم هذا الرمز لإكمال عملية التسجيل في Endak.\n\nإذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة.";

            $sender = \App\Models\WhatsappSender::first();
            if ($sender) {
                sendWhatsAppMessage($whatsappPhone, $message, $sender->number, $sender->token, $sender->instance_id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى هاتفك عبر الواتساب',
                'otp' => $otpCode->code // إضافة رمز OTP للعرض في التطوير
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|digits:4'
            ]);

            // التحقق من وجود بيانات المستخدم في الجلسة
            if (!session()->has('register_user_id')) {
                return response()->json(['error' => 'انتهت جلسة التسجيل، يرجى البدء من جديد'], 401);
            }

            $user = User::find(session('register_user_id'));
            if (!$user) {
                return response()->json(['error' => 'المستخدم غير موجود'], 404);
            }

            // التحقق من صحة رمز OTP
            $otpVerified = OtpCode::verifyOtp($user->phone, $request->otp, 'registration');

            if (!$otpVerified) {
                return response()->json(['error' => 'كود التحقق غير صحيح أو منتهي الصلاحية'], 422);
            }

            // تحديث حالة المستخدم
            $selectedRole = session('selected_role', $user->role_id);
            $user->update([
                'status' => 'active',
                'role_id' => $selectedRole,
                'role_name' => $selectedRole == 1 ? 'user' : 'provider'
            ]);
            Log::info('User status after update', ['id' => $user->id, 'status' => $user->fresh()->status]);

            // تسجيل الدخول تلقائياً
            auth()->login($user);

            // تنظيف الجلسة
            session()->forget(['register_user_id', 'selected_role', 'country_code']);

            return response()->json([
                'success' => true,
                'redirect' => route('home')
            ]);

        } catch (\Exception $e) {
            Log::error('verifyOtp Exception: ' . $e->getMessage());
            return response()->json([
                'error' => 'حدث خطأ غير متوقع: ' . $e->getMessage()
            ], 500);
        }
    }

public function resendOtp(Request $request)
{
    if (!session()->has('register_user_id')) {
        return response()->json(['error' => 'انتهت جلسة التسجيل'], 401);
    }

    $user = User::find(session('register_user_id'));
    if (!$user) {
        return response()->json(['error' => 'المستخدم غير موجود'], 404);
    }

    // إنشاء رمز OTP جديد
    $otpCode = OtpCode::createOtp($user->phone, 'registration', 10);

    // إرسال OTP الجديد عبر الواتساب
    $countryCode = session('country_code', '+966');
    $whatsappPhone = $countryCode . $user->phone;
    $message = "مرحباً {$user->first_name}!\n\nرمز التحقق الجديد الخاص بك هو: {$otpCode->code}\n\nاستخدم هذا الرمز لإكمال عملية التسجيل في Endak.\n\nإذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة.";

    $sender = \App\Models\WhatsappSender::first();
    if ($sender) {
        sendWhatsAppMessage($whatsappPhone, $message, $sender->number, $sender->token, $sender->instance_id);
    }

    return response()->json([
        'success' => true,
        'message' => 'تم إرسال رمز تحقق جديد إلى هاتفك عبر الواتساب',
        'otp' => $otpCode->code // إضافة رمز OTP للعرض في التطوير
    ]);
}

    public function showActivateForm()
    {
        if (!auth()->check()) {
            return redirect()->route('login-page');
        }
        if (auth()->user()->status === 'active') {
            return redirect()->route('home');
        }
        return view('front_office.auth.activate_phone');
    }

    public function postActivatePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:7|max:20',
        ]);
        $user = auth()->user();
        $user->phone = $request->phone;
        $user->save();
        // إرسال كود OTP
        $otpCode = \App\Models\OtpCode::createOtp($user->phone, 'registration', 10);
        // إرسال الكود عبر الواتساب أو SMS
        $countryCode = $user->countryObj ? $user->countryObj->code : '+966';
        $whatsappPhone = $countryCode . $user->phone;
        $message = "مرحباً {$user->first_name}!\n\nرمز التحقق الخاص بك هو: {$otpCode->code}\n\nاستخدم هذا الرمز لإكمال تفعيل حسابك في Endak.";
        $sender = \App\Models\WhatsappSender::first();
        if ($sender) {
            sendWhatsAppMessage($whatsappPhone, $message, $sender->number, $sender->token, $sender->instance_id);
        }
        // حفظ بيانات التفعيل في الجلسة
        session(['register_user_id' => $user->id, 'country_code' => $countryCode]);
        return redirect()->route('otp.form')->with('message', 'تم إرسال رمز التحقق إلى هاتفك.');
    }

    public function showOtpForm()
    {
        if (!session('register_user_id')) {
            return redirect()->route('activate_phone')->with('error', 'الرجاء تفعيل الهاتف أولاً');
        }
        return view('auth.otp');
    }

    public function getCurrentOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string'
            ]);

            $otpCode = OtpCode::getCurrentOtp($request->phone, 'registration');

            if ($otpCode) {
                return response()->json([
                    'success' => true,
                    'otp' => $otpCode
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على رمز تحقق صالح'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع: ' . $e->getMessage()
            ], 500);
        }
    }
}
