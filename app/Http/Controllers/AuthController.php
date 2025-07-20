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
        $govers = Governements::all();

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

        // إذا لم يكن مفعل، أرسله لصفحة التحقق
        if ($user->status !== 'active') {
            Log::info('User not active, redirecting to verification', ['user_id' => $user->id]);
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
            // حوله لصفحة التحقق
            return redirect()->route('register-page')->with('message', 'يجب عليك تفعيل حسابك عبر رمز التحقق المرسل إلى هاتفك.');
        }

        // التحقق من كلمة المرور
        if (!Hash::check($request['password'], $user->password)) {
            return back()->withErrors([
                'loginError' => 'كلمة المرور غير صحيحة.'
            ]);
        }

        // تسجيل الدخول
        Auth::login($user);
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
                'password' => "required|digits:6|confirmed",
                'phone' => 'required|string|min:7|max:20|unique:users',
                'email' => 'nullable|email|unique:users',
            ], [
                'phone.string' => 'رقم الهاتف غير صحيح',
                'phone.min' => 'رقم الهاتف يجب أن يكون 7 أرقام على الأقل',
                'phone.max' => 'رقم الهاتف يجب أن يكون 20 رقم على الأكثر',
            ]);

            // تنظيف رقم الهاتف
            $phone = $request->phone;
            // إزالة 00 أو + من البداية إذا وجدت
            $phone = preg_replace('/^(\+|00)/', '', $phone);
            // إزالة أي أحرف غير رقمية
            $phone = preg_replace('/[^0-9]/', '', $phone);

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
                'message' => 'تم إرسال رمز التحقق إلى هاتفك عبر الواتساب'
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
            $user->update([
                'status' => 'active',
                'role_id' => session('selected_role'),
                'role_name' => session('selected_role') == 1 ? 'user' : 'provider'
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
        'message' => 'تم إرسال رمز تحقق جديد إلى هاتفك عبر الواتساب'
    ]);
}
}
