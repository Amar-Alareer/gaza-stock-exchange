<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Item;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AutomationController extends Controller
{
    public function getFacePrices($id)
    {
        // 1. جلب بيانات المتجر مع المنطقة المرتبطة به
        $store = Store::with('region')->find($id);

        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'المتجر غير موجود'
            ], 404);
        }

        // 2. الحصول على رابط الفيس بوك
        $facebookUrl = $store->facebook_url; 

        if (!$facebookUrl) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد رابط فيس بوك مرتبط بهذا المتجر'
            ], 400);
        }

        // 3. رابط n8n
        $n8nWebhookUrl = env('N8N_WEBHOOK_URL', 'http://localhost:5678/webhook-test/gaza-stock');   

        try {
            // إرسال الطلب لـ n8n
            $response = Http::post($n8nWebhookUrl, [
                'store_id' => $store->id,
                'facebook_url' => $facebookUrl,
            ]);

            if ($response->successful()) {
                $scrapedData = $response->json();
                $savedPrices = [];

                // المرونة في قراءة البيانات إذا كانت داخل 'products' أو مصفوفة مباشرة
                $productsList = [];
                if (isset($scrapedData['products']) && is_array($scrapedData['products'])) {
                    $productsList = $scrapedData['products'];
                } elseif (isset($scrapedData[0]['products']) && is_array($scrapedData[0]['products'])) {
                    $productsList = $scrapedData[0]['products'];
                }

                // 4. معالجة وتخزين المنتجات والأسعار
                if (!empty($productsList)) {
                    DB::transaction(function () use ($productsList, $store, &$savedPrices) {
                        foreach ($productsList as $productData) {
                            
                            $productName = trim($productData['product'] ?? '');
                            $priceValue = $productData['price'] ?? null;

                            // تخطي العناصر التي لا تحتوي على اسم أو سعر
                            if (empty($productName) || is_null($priceValue)) {
                                continue;
                            }

                            // أ) البحث عن المنتج في جدول items، وإن لم يوجد يتم إنشاؤه وتوليد ID له
                            $item = Item::firstOrCreate(
                                ['name' => $productName]
                            );

                            // ب) تسجيل السعر الجديد للمنتج في جدول prices مرجوعاً لـ store_id و item_id
                            $priceRecord = Price::create([
                                'store_id' => $store->id,
                                'item_id'  => $item->id,
                                'price'    => $priceValue,
                            ]);

                            $savedPrices[] = [
                                'item_id'   => $item->id,
                                'item_name' => $item->name,
                                'price'     => $priceRecord->price,
                                'created_at' => $priceRecord->created_at
                            ];
                        }
                    });
                }

                // 5. إرجاع النتيجة متضمنة البيانات المخزنة حديثاً
                return response()->json([
                    'status'       => 'success',
                    'message'      => 'تم جلب الأسعار وتخزينها بنجاح في قاعدة البيانات',
                    'store'        => $store,
                    'total_saved'  => count($savedPrices),
                    'saved_prices' => $savedPrices
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'فشل الاتصال بـ n8n',
                'details' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'تعذر الاتصال بـ n8n: ' . $e->getMessage()
            ], 500);
        }
    }
}
