<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Complaints;
use App\Models\Price;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * جلب جميع الإشعارات المجمعة ديناميكياً من كافة التحديثات في النظام
     */
    public function getNotifications(Request $request)
    {
        $readIds = $request->query('read_ids', []);
        if (is_string($readIds)) {
            $readIds = array_filter(explode(',', $readIds));
        }

        $notifications = collect();

        // 1. إشعارات المتاجر (إضافة وتعديل)
        try {
            $stores = Store::with('region')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($stores as $store) {
                $isNew = $store->created_at && $store->updated_at && $store->created_at->diffInSeconds($store->updated_at) < 5;
                $notifId = $isNew ? 'store_create_'.$store->id : 'store_update_'.$store->id.'_'.$store->updated_at->timestamp;
                $timestamp = $store->updated_at ?? $store->created_at;

                $notifications->push([
                    'id' => $notifId,
                    'type' => 'store',
                    'title' => $isNew ? 'متجر جديد' : 'تحديث متجر',
                    'message' => $isNew 
                        ? 'تم إضافة المتجر الجديد "'.$store->name.'" إلى النظام.' 
                        : 'تم تحديث بيانات المتجر "'.$store->name.'".',
                    'link' => '/stores',
                    'timestamp' => $timestamp ? $timestamp->toIso8601String() : now()->toIso8601String(),
                    'raw_time' => $timestamp ? $timestamp->timestamp : 0,
                    'time_ago' => $timestamp ? $this->humanTimeArabic($timestamp) : 'مؤخراً',
                    'is_read' => in_array($notifId, $readIds),
                ]);
            }
        } catch (\Exception $e) {
            // يتخطى الأخطاء إن وجدت لضمان عدم توقف النظام
        }

        // 2. إشعارات تحديث المنتجات والأسعار
        try {
            $prices = Price::with(['item', 'store'])
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($prices as $price) {
                $itemName = $price->item->name ?? 'منتج';
                $storeName = $price->store->name ?? 'متجر';
                $notifId = 'price_update_'.$price->id.'_'.($price->updated_at ? $price->updated_at->timestamp : rand(1000, 9999));
                $timestamp = $price->updated_at ?? $price->created_at;

                $notifications->push([
                    'id' => $notifId,
                    'type' => 'product',
                    'title' => 'تحديث سعر/منتج',
                    'message' => 'تم تحديث سعر "'.$itemName.'" في "'.$storeName.'" إلى '.$price->price.' شيكل.',
                    'link' => '/products',
                    'timestamp' => $timestamp ? $timestamp->toIso8601String() : now()->toIso8601String(),
                    'raw_time' => $timestamp ? $timestamp->timestamp : 0,
                    'time_ago' => $timestamp ? $this->humanTimeArabic($timestamp) : 'مؤخراً',
                    'is_read' => in_array($notifId, $readIds),
                ]);
            }
        } catch (\Exception $e) {
        }

        // 3. إشعارات المقالات
        try {
            $articles = Article::orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($articles as $article) {
                $notifId = 'article_'.$article->id.'_'.($article->updated_at ? $article->updated_at->timestamp : rand(1000, 9999));
                $timestamp = $article->updated_at ?? $article->created_at;

                $notifications->push([
                    'id' => $notifId,
                    'type' => 'article',
                    'title' => 'مقال جديد / محدث',
                    'message' => 'تم نشر أو تعديل مقال بعنوان "'.$article->title.'".',
                    'link' => '/articles',
                    'timestamp' => $timestamp ? $timestamp->toIso8601String() : now()->toIso8601String(),
                    'raw_time' => $timestamp ? $timestamp->timestamp : 0,
                    'time_ago' => $timestamp ? $this->humanTimeArabic($timestamp) : 'مؤخراً',
                    'is_read' => in_array($notifId, $readIds),
                ]);
            }
        } catch (\Exception $e) {
        }

        // 4. إشعارات الشكاوى
        try {
            $complaints = Complaints::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($complaints as $complaint) {
                $notifId = 'complaint_'.$complaint->id;
                $timestamp = $complaint->created_at;

                $notifications->push([
                    'id' => $notifId,
                    'type' => 'complaint',
                    'title' => 'شكوى جديدة',
                    'message' => 'تم استلام شكوى جديدة تتعلق بالخدمة أو الأسعار.',
                    'link' => '/stores',
                    'timestamp' => $timestamp ? $timestamp->toIso8601String() : now()->toIso8601String(),
                    'raw_time' => $timestamp ? $timestamp->timestamp : 0,
                    'time_ago' => $timestamp ? $this->humanTimeArabic($timestamp) : 'مؤخراً',
                    'is_read' => in_array($notifId, $readIds),
                ]);
            }
        } catch (\Exception $e) {
        }

        // ترتيب كافة الإشعارات تنازلياً حسب التوقيت بأحدث الأشياء أولاً
        $sorted = $notifications->sortByDesc('raw_time')->values();

        // حساب عدد غير المقروء
        $unreadCount = $sorted->where('is_read', false)->count();

        return response()->json([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'notifications' => $sorted,
        ], 200);
    }

    /**
     * تحويل التاريخ إلى صيغة نسبية بالعربية
     */
    private function humanTimeArabic(?Carbon $date): string
    {
        if (! $date) {
            return 'مؤخراً';
        }

        $now = Carbon::now();
        $diffSeconds = $now->diffInSeconds($date);

        if ($diffSeconds < 60) {
            return 'قبل أقل من دقيقة';
        }

        $diffMinutes = $now->diffInMinutes($date);
        if ($diffMinutes < 60) {
            return "قبل {$diffMinutes} دقيقة";
        }

        $diffHours = $now->diffInHours($date);
        if ($diffHours < 24) {
            return "قبل {$diffHours} ساعة";
        }

        $diffDays = $now->diffInDays($date);
        if ($diffDays < 7) {
            return "قبل {$diffDays} يوم";
        }

        return $date->format('Y-m-d');
    }
}
