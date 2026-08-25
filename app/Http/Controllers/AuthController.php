<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Store;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ============================
    //  WEB VIEW PAGES
    // ============================

    public function loginView()
    {
        if (Auth::check()) {
            return redirect()->route('profile');
        }
        return view('login');
    }

    public function signupView()
    {
        if (Auth::check()) {
            return redirect()->route('profile');
        }
        $regions = \App\Models\Region::orderBy('city_or_governorate')->get();
        return view('signup', compact('regions'));
    }

    public function profile()
    {
        // If not logged in, show guest profile page
        if (!Auth::check()) {
            return view('profile', ['user' => null, 'favoriteItems' => collect(), 'favoriteStores' => collect()]);
        }

        $user = Auth::user()->load('region');

        // Fetch favorite items
        $favoriteItemIds = UserFavorite::where('user_id', $user->id)
            ->where('type', 'item')
            ->pluck('reference_id');

        $favoriteItems = Item::with(['prices.store', 'categoryRelation'])
            ->whereIn('id', $favoriteItemIds)
            ->get();

        // Fetch favorite stores
        $favoriteStoreIds = UserFavorite::where('user_id', $user->id)
            ->where('type', 'store')
            ->pluck('reference_id');

        $favoriteStores = Store::with('region')
            ->whereIn('id', $favoriteStoreIds)
            ->get();

        return view('profile', compact('user', 'favoriteItems', 'favoriteStores'));
    }

    // ============================
    //  WEB SESSION AUTH
    // ============================

    public function webLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required'    => 'يرجى إدخال البريد الإلكتروني أو اسم المستخدم.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        $credentials_email    = ['email'    => $request->email, 'password' => $request->password];
        $credentials_username = ['username' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials_email, $request->boolean('remember'))
            || Auth::attempt($credentials_username, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('profile'))->with('success', 'مرحباً بك! تم تسجيل دخولك بنجاح.');
        }

        return back()->withErrors(['email' => 'البيانات المدخلة غير صحيحة.'])->withInput();
    }

    public function webRegister(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|string|min:6',
            'region_id' => 'nullable|exists:regions,id',
        ], [
            'name.required'     => 'يرجى إدخال الاسم الكامل.',
            'email.required'    => 'يرجى إدخال البريد الإلكتروني.',
            'email.unique'      => 'البريد الإلكتروني مسجل مسبقاً.',
            'password.min'      => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        $defaultRegionId = $request->region_id ?: (\App\Models\Region::first()->id ?? null);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'region_id' => $defaultRegionId,
            'password'  => Hash::make($request->password),
            'role'      => 'client',
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('profile'))->with('success', 'تم إنشاء حسابك بنجاح! مرحباً بك في وفر كاش.');
    }

    public function webLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    // ============================
    //  GOOGLE OAUTH
    // ============================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find user by google_id or email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                $user->google_id = $googleUser->getId();
                if (!$user->profile_picture && $googleUser->getAvatar()) {
                    $user->profile_picture = $googleUser->getAvatar();
                }
                $user->save();
            } else {
                $defaultRegionId = \App\Models\Region::first()->id ?? null;
                $user = User::create([
                    'name'            => $googleUser->getName() ?? $googleUser->getNickname() ?? 'مستخدم جديد',
                    'email'           => $googleUser->getEmail(),
                    'google_id'       => $googleUser->getId(),
                    'profile_picture' => $googleUser->getAvatar(),
                    'region_id'       => $defaultRegionId,
                    'password'        => Hash::make(Str::random(24)),
                    'role'            => 'client',
                ]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('profile'))->with('success', 'مرحباً بك! تم تسجيل الدخول عبر حساب جوجل بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'تعذر تسجيل الدخول عبر جوجل: ' . $e->getMessage()]);
        }
    }

    // ============================
    //  PROFILE UPDATE
    // ============================

    public function updateWebProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'current_password'=> 'nullable|string',
            'new_password'    => 'nullable|string|min:6',
        ], [
            'name.required'         => 'يرجى كتابة الاسم الكامل.',
            'profile_picture.image' => 'يجب أن يكون الملف المرفوع صورة صالحة.',
            'profile_picture.max'   => 'حجم الصورة يجب أن لا يتجاوز 10 ميجابايت.',
            'new_password.min'      => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل.',
        ]);

        // Validate current password if changing password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->name    = $request->name;
        $user->phone   = $request->phone;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('profile', ['tab' => 'settings'])->with('success', 'تم حفظ وتحديث بيانات الملف الشخصي بنجاح ✅');
    }

    // ============================
    //  FAVORITES SYSTEM
    // ============================

    public function toggleFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthenticated', 'message' => 'يرجى تسجيل الدخول أولاً'], 401);
        }

        $request->validate([
            'type'         => 'required|in:item,store',
            'reference_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $existing = UserFavorite::where([
            'user_id'      => $user->id,
            'type'         => $request->type,
            'reference_id' => $request->reference_id,
        ])->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'تمت إزالته من المفضلة']);
        }

        UserFavorite::create([
            'user_id'      => $user->id,
            'type'         => $request->type,
            'reference_id' => $request->reference_id,
        ]);

        return response()->json(['status' => 'added', 'message' => 'تمت الإضافة إلى المفضلة ⭐']);
    }

    public function checkFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $exists = UserFavorite::where([
            'user_id'      => Auth::id(),
            'type'         => $request->type,
            'reference_id' => $request->reference_id,
        ])->exists();

        return response()->json(['is_favorite' => $exists]);
    }

    // ============================
    //  API METHODS (kept for compatibility)
    // ============================

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'region_id' => 'nullable|exists:regions,id',
            'phone'     => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'region_id' => $request->region_id ?? null,
            'phone'     => $request->phone ?? null,
            'role'      => 'client',
        ]);

        return response()->json([
            'message' => 'تم تسجيل حساب عميل جديد بنجاح',
            'user'    => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->username)
                  ->orWhere('username', $request->username)
                  ->orWhere('name', $request->username);
        })->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'البيانات المدخلة غير صحيحة.'], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token'   => $token,
            'user'    => [
                'id'                  => $user->id,
                'name'                => $user->name,
                'username'            => $user->username,
                'email'               => $user->email,
                'phone'               => $user->phone,
                'role'                => $user->role ?? 'client',
                'profile_picture_url' => $user->profile_picture_url,
            ],
            'message' => 'تم تسجيل الدخول بنجاح',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح'], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $user->append('profile_picture_url');

        return response()->json([
            'status' => 'success',
            'user'   => [
                'name'                => $user->name,
                'username'            => $user->username,
                'email'               => $user->email,
                'phone'               => $user->phone,
                'address'             => $user->address,
                'profile_picture_url' => $user->profile_picture_url,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'             => 'required|string|max:255',
            'username'         => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'email'            => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone'            => 'nullable|string|max:50',
            'address'          => 'nullable|string|max:255',
            'profile_picture'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:6',
        ]);

        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->address  = $request->address;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'كلمة المرور الحالية غير صحيحة.'], 400);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        $user->append('profile_picture_url');

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'user'    => [
                'name'                => $user->name,
                'username'            => $user->username,
                'email'               => $user->email,
                'phone'               => $user->phone,
                'address'             => $user->address,
                'profile_picture_url' => $user->profile_picture_url,
            ],
        ]);
    }
}
