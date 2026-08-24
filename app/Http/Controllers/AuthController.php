<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class AuthController extends Controller
{

    public function login(Request $request)
    {
        // 1. التحقق من المدخلات
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ]);

        // 2. البحث عن المستخدم
        $user = User::where('email', $request->username)
            ->orWhere('name', $request->username)
            ->first();

        // 3. التحقق من كلمة المرور
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'البيانات المدخلة غير صحيحة.'
            ], 421);
        }

        // 4. إنشاء التوكن (Token) وإرجاعه للوحة Vue
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => 'admin', // اللوحة تتوقع رول للآدمن لتوجيهه للـ Dashboard
            ],
            'message' => 'تم تسجيل الدخول بنجاح'
        ], 200);
    }


    public function logout(Request $request)
    {
        // حذف التوكن الحالي عند تسجيل الخروج
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }
}
