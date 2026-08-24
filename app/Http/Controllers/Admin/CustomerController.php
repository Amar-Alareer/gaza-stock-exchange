<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * عرض قائمة العملاء مع الفلترة والبحث والإحصائيات
     */
    public function index(Request $request)
    {
        $query = User::with('region')->where('role', 'client');

        // فلترة بالمنطقة
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // بحث نصي (الاسم، البريد، الهاتف، اسم المستخدم)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->get()->map(function ($user) {
            return $this->formatUser($user);
        });

        // إحصائيات سريعة للعملاء
        $totalCustomers = User::where('role', 'client')->count();
        $thisMonthCustomers = User::where('role', 'client')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $withPhoneCount = User::where('role', 'client')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->count();

        $regions = Region::orderBy('city_or_governorate')->get(['id', 'city_or_governorate', 'area_name']);

        return response()->json([
            'status' => 'success',
            'customers' => $customers,
            'regions' => $regions,
            'stats' => [
                'total' => $totalCustomers,
                'this_month' => $thisMonthCustomers,
                'with_phone' => $withPhoneCount,
            ],
        ]);
    }

    /**
     * إضافة عميل جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'nullable|string|max:100|unique:users,username',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'region_id' => 'nullable|exists:regions,id',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'اسم العميل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'username.unique' => 'اسم المستخدم مأخوذ بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 6 خانات',
            'region_id.exists' => 'المنطقة المحددة غير صالحة',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username ?: null,
            'phone' => $request->phone,
            'address' => $request->address,
            'region_id' => $request->region_id ?: null,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        $user->load('region');

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إضافة العميل بنجاح',
            'customer' => $this->formatUser($user),
        ], 201);
    }

    /**
     * عرض تفاصيل عميل محدد
     */
    public function show($id)
    {
        $user = User::with('region')->where('role', 'client')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'customer' => $this->formatUser($user),
        ]);
    }

    /**
     * تحديث بيانات عميل
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'client')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:100', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'region_id' => 'nullable|exists:regions,id',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'اسم العميل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل لحساب آخر',
            'username.unique' => 'اسم المستخدم مستخدم بالفعل لحساب آخر',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 6 خانات',
            'region_id.exists' => 'المنطقة المحددة غير صالحة',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username ?: null,
            'phone' => $request->phone,
            'address' => $request->address,
            'region_id' => $request->region_id ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->load('region');

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث بيانات العميل بنجاح',
            'customer' => $this->formatUser($user),
        ]);
    }

    /**
     * حذف عميل
     */
    public function destroy($id)
    {
        $user = User::where('role', 'client')->findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف حساب العميل بنجاح',
        ]);
    }

    /**
     * تنسيق بيانات العميل للعرض
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'profile_picture_url' => $user->profile_picture_url,
            'avatar' => $user->profile_picture_url,
            'region_id' => $user->region_id,
            'region' => $user->region ? [
                'id' => $user->region->id,
                'city_or_governorate' => $user->region->city_or_governorate,
                'area_name' => $user->region->area_name,
                'display' => $user->region->city_or_governorate . ($user->region->area_name ? ' - ' . $user->region->area_name : ''),
            ] : null,
            'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i') : null,
            'created_at_human' => $user->created_at ? $user->created_at->diffForHumans() : null,
        ];
    }
}
