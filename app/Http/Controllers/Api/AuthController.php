<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Auth\UserServices;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }
    // login function
    public function login(Request $request)
    {

        // $loginResponse = $this->userService->login($request->all());
        // if ($loginResponse != false  ) {
        //     $user = $request->user();
        //     $user['rate'] = $user->rates() ;
        //     $token = $user->createToken("$user->first_name");
        //     return response()->apiSuccess(['token' => $token->plainTextToken, 'user' => $user]);
        // }else{
        //     return response()->apiFail('Unauthorised' ,  401);
        // }



        $validated = $request->validate([
            'phone' => 'required|numeric|exists:users,phone', // تحقق من رقم الهاتف
            'password' => 'required|string',
        ]);

        // التحقق من بيانات المستخدم
        $user = User::where('phone', $request->phone)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // إضافة تقييم المستخدم (إن وجد)
            $user['rate'] = $user->rates();

            // إنشاء التوكن
            $token = $user->createToken($user->first_name)->plainTextToken;

            // استجابة ناجحة مع التوكن
            return response()->apiSuccess([
                'token' => $token,
                'user' => $user
            ]);
        }

        // في حالة عدم وجود المستخدم أو كلمة المرور غير صحيحة
        return response()->apiFail('Unauthorized', 401);

    }

    public function register(Request $request)
    {

        $user = $this->userService->createUser($request);
        $message['user'] = $user;
        $message['token'] = $user->createToken("user")->plainTextToken;

        return response()->apiSuccess($message);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'massege' => 'User Successfully logout',
        ], 200);
    }
}
