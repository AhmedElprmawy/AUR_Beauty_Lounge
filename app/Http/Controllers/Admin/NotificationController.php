<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * جلب الإشعارات غير المقروءة
     */
    public function unread()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مسجل الدخول'
                ], 401);
            }

            // ✅ التحقق من وجود جدول notifications
            if (!\Schema::hasTable('notifications')) {
                return response()->json([
                    'success' => false,
                    'message' => 'جدول الإشعارات غير موجود'
                ], 500);
            }

            $notifications = $user->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedNotifications = [];

            foreach ($notifications as $notification) {
                $data = $notification->data ?? [];
                
                $formattedNotifications[] = [
                    'id' => $notification->id,
                    'booking_id' => $data['booking_id'] ?? null,
                    'customer_name' => $data['customer_name'] ?? 'غير معروف',
                    'service_name' => $data['service_name'] ?? 'خدمة',
                    'date' => $data['date'] ?? null,
                    'time' => $data['time'] ?? null,
                    'message' => $data['message'] ?? null,
                    'created_at' => $notification->created_at ? $notification->created_at->diffForHumans() : 'منذ قليل',
                    'type' => $data['type'] ?? 'general',
                    'read_at' => $notification->read_at,
                ];
            }

            return response()->json([
                'success' => true,
                'count' => $notifications->count(),
                'notifications' => $formattedNotifications
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في جلب الإشعارات: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب جميع الإشعارات
     */
    public function all()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مسجل الدخول'
                ], 401);
            }

            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $formattedNotifications = [];

            foreach ($notifications as $notification) {
                $data = $notification->data ?? [];
                
                $formattedNotifications[] = [
                    'id' => $notification->id,
                    'booking_id' => $data['booking_id'] ?? null,
                    'customer_name' => $data['customer_name'] ?? 'غير معروف',
                    'service_name' => $data['service_name'] ?? 'خدمة',
                    'date' => $data['date'] ?? null,
                    'time' => $data['time'] ?? null,
                    'message' => $data['message'] ?? null,
                    'created_at' => $notification->created_at ? $notification->created_at->diffForHumans() : 'منذ قليل',
                    'read_at' => $notification->read_at,
                    'is_read' => !is_null($notification->read_at),
                ];
            }

            return response()->json([
                'success' => true,
                'notifications' => $formattedNotifications
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في جلب جميع الإشعارات: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث إشعار إلى مقروء
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مسجل الدخول'
                ], 401);
            }

            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => '✅ تم تحديث الإشعار'
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث الإشعار: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث جميع الإشعارات إلى مقروءة
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مسجل الدخول'
                ], 401);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => '✅ تم تحديث جميع الإشعارات'
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث جميع الإشعارات: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف إشعار
     */
    public function delete($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مسجل الدخول'
                ], 401);
            }

            $notification = $user->notifications()->findOrFail($id);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => '✅ تم حذف الإشعار'
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في حذف الإشعار: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}