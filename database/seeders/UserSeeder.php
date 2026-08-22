<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود مناطق
        $gazaRegion = Region::firstOrCreate(
            ['city_or_governorate' => 'غزة', 'area_name' => 'الرمال الشمالي']
        );
        $khanyounisRegion = Region::firstOrCreate(
            ['city_or_governorate' => 'خانيونس', 'area_name' => 'البلد']
        );

        // 1. حساب مسؤول النظام الرئيسي (Admin)
        User::firstOrCreate(
            ['email' => 'admin@wafar.cash'],
            [
                'name' => 'مدير النظام',
                'username' => 'admin',
                'password' => Hash::make('admin123456'),
                'role' => 'admin', // دور المسؤول
                'phone' => '0599000000',
                'address' => 'غزة - فلسطين',
                'region_id' => $gazaRegion->id,
            ]
        );

        // 2. حساب عميل تجريبي 1 (Client)
        User::firstOrCreate(
            ['email' => 'client@wafar.cash'],
            [
                'name' => 'محمد أحمد النجار',
                'username' => 'mohammed_najjar',
                'password' => Hash::make('client123456'),
                'role' => 'client', // دور العميل
                'phone' => '0599123456',
                'address' => 'غزة - الرمال الشمالي',
                'region_id' => $gazaRegion->id,
            ]
        );

        // 3. حساب عميل تجريبي 2 (Client)
        User::firstOrCreate(
            ['email' => 'sara@wafar.cash'],
            [
                'name' => 'سارة محمود كلاب',
                'username' => 'sara_kellab',
                'password' => Hash::make('client123456'),
                'role' => 'client', // دور العميل
                'phone' => '0598765432',
                'address' => 'خانيونس - البلد',
                'region_id' => $khanyounisRegion->id,
            ]
        );
    }
}
