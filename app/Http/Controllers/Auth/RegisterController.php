<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Rules\ValidPhoneNumber;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register_steps');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:users,phone',
                'email' => 'nullable|email|unique:users,email',
                'country' => 'required|exists:countries,id',
                'governement' => 'required|exists:governements,id',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // إذا كان الطلب AJAX (من خطوات التسجيل)
            if ($request->ajax()) {
                // أنشئ المستخدم بحالة pending فقط إذا لم يكن موجود في السيشن
                if (!session('register_user_id')) {
                    $user = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'country' => $request->country,
                        'governement' => $request->governement,
                        'password' => Hash::make($request->password),
                        'status' => 'pending',
                        'role_name' => 'pending',
                    ]);
                    session(['register_user_id' => $user->id]);
                } else {
                    $user = User::find(session('register_user_id'));
                }
                // توليد كود OTP وحفظه في السيشن
                $otp = rand(1000, 9999);
                session(['otp' => $otp]);
                // يمكنك هنا إرسال الكود فعليًا عبر SMS إذا أردت
                return response()->json(['otp' => $otp]);
            }

            // الطلب العادي (من فورم واحد)
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'country' => $request->country,
                'governement' => $request->governement,
                'password' => Hash::make($request->password),
                'status' => 'pending',
                'role_name' => 'pending',
            ]);
            $otp = rand(1000, 9999);
            session(['register_user_id' => $user->id, 'otp' => $otp]);
            // يمكنك هنا إرسال الكود فعليًا عبر SMS إذا أردت
            return redirect()->route('otp.form')->with('otp_demo', $otp);
        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function showOtpForm()
    {
        if (!session('register_user_id')) {
            return redirect()->route('register')->with('error', 'الرجاء إكمال عملية التسجيل أولاً');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:4',
        ]);

        if ($request->otp != session('otp')) {
            return response()->json(['error' => 'كود التفعيل غير صحيح'], 422);
        }

        $user = User::findOrFail(Session::get('register_user_id'));
        $user->update(['status' => 'active']);

        return redirect()->route('choose.role');
    }

    public function showRoleForm()
    {
        if (!session('register_user_id')) {
            return redirect()->route('register')->with('error', 'الرجاء إكمال عملية التسجيل أولاً');
        }

        return view('auth.choose_role');
    }

    public function saveRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:1,3',
        ]);

        $user = User::findOrFail(Session::get('register_user_id'));
        $role_id = (int)$request->role;
        $role_name = $role_id === 1 ? 'الرول 1' : 'الرول 3';
        $user->update([
            'role_id' => $role_id,
            'role_name' => $role_name
        ]);

        auth()->login($user);
        Session::forget(['otp', 'register_user_id']);

        return redirect('/')
            ->with('success', 'تم تسجيلك بنجاح!');
    }

    public function resendOtp(Request $request)
    {
        $otp = rand(1000, 9999);
        session(['otp' => $otp]);
        // يمكنك هنا إرسال الكود فعليًا عبر SMS إذا أردت
        return response()->json(['otp' => $otp]);
    }
}
