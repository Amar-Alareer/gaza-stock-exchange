<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. التحقق من المدخلات
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // 2. البحث عن المستخدم
        $user = User::where('email', $request->username)
            ->orWhere('username', $request->username)
            ->orWhere('name', $request->username)
            ->first();

        // 3. التحقق من كلمة المرور
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'البيانات المدخلة غير صحيحة.',
            ], 401);
        }

        // 4. إنشاء التوكن (Token) وإرجاعه للوحة Vue
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin', // اللوحة تتوقع رول للآدمن لتوجيهه للـ Dashboard
            ],
            'message' => 'تم تسجيل الدخول بنجاح',
        ], 200);
    }

    public function logout(Request $request)
    {
        // حذف التوكن الحالي عند تسجيل الخروج
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $user->append('profile_picture_url');

        return response()->json([
            'status' => 'success',
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'profile_picture_url' => $user->profile_picture_url,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
        ], [
            'profile_picture.max' => 'حجم الصورة يجب أن لا يتجاوز 10 ميجابايت.',
            'profile_picture.image' => 'يجب أن يكون الملف صورة.',
            'profile_picture.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif, webp.',
            'username.unique' => 'اسم المستخدم محجوز مسبقاً.',
            'email.unique' => 'البريد الإلكتروني محجوز مسبقاً.',
            'new_password.min' => 'يجب أن تتكون كلمة المرور الجديدة من 6 أحرف على الأقل.',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'كلمة المرور الحالية غير صحيحة.',
                ], 400);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        $user->append('profile_picture_url');

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'profile_picture_url' => $user->profile_picture_url,
            ],
        ]);
    }
}
