<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Region;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تحديد أو إنشاء المناطق
        $middleRegion = Region::firstOrCreate([
            'city_or_governorate' => 'دير البلح',
            'area_name'           => 'المنطقة الوسطى',
        ]);

        // إذا كان هناك متاجر في مناطِق أخرى (مثلاً غزة أو خان يونس) يمكنك إنشاء المنطقة الخاصة بها هنا:
        /*
        $gazaRegion = Region::firstOrCreate([
            'city_or_governorate' => 'غزة',
            'area_name'           => 'الرمال',
        ]);
        */

        // 2. قائمة المتاجر المراد إضافتها
        $stores = [
            [
                'name'          => 'البابا مول',
                'phone'         => '0599239700',
                'latitude'      => '31.441778',
                'longitude'     => '34.404611',
                'facebook_url'  => 'https://www.facebook.com/profile.php?id=100057379842317',
                'instagram_url' => null,
                'telegram_url'  => null,
                'region_id'     => $middleRegion->id,
            ],
            // ➕ أضف المتاجر الجديدة هنا بهذه الطريقة:
            [
                'name'          => 'ابو ندى ',
                'phone'         => '', // اختياري
                'latitude'      => null,        // اختياري
                'longitude'     => null,        // اختياري
                'facebook_url'  => 'https://www.facebook.com/AbuNada.Market',
                'instagram_url' => null,
                'telegram_url'  => null,
                'region_id'     => $middleRegion->id,
            ],
        ];

        // 3. التكرار وإنشاء أو تحديث المتاجر لمنع التكرار
        foreach ($stores as $storeData) {
            Store::updateOrCreate(
                ['name' => $storeData['name']], // البحث بأسماء المتاجر لمنع التكرار
                $storeData
            );
        }
    }
}