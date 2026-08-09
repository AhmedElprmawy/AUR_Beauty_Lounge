<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CustomService;
use Illuminate\Http\Request;

class CustomServiceController extends Controller
{
    /**
     * عرض جميع الخدمات اللحظية لحجز معين
     */
    public function index($bookingId)
    {
        try {
            $booking = Booking::with('customServices')->findOrFail($bookingId);
            
            return response()->json([
                'success' => true,
                'data' => $booking->customServices,
                'total' => $booking->customServices->sum('price'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✅ إضافة خدمة لحظية جديدة (بدون تعديل total_price)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0.01',
                'notes' => 'nullable|string',
            ]);

            $booking = Booking::find($validated['booking_id']);
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ الحجز غير موجود',
                ], 404);
            }

            // ✅ إنشاء الخدمة اللحظية فقط (لا نعدل total_price)
            $customService = CustomService::create([
                'booking_id' => $validated['booking_id'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'notes' => $validated['notes'] ?? null,
                'staff_id' => null,
            ]);

            // ✅ إعادة تحميل العلاقة للحصول على القيم الجديدة
            $booking->load('customServices');

            return response()->json([
                'success' => true,
                'message' => '✅ تم إضافة الخدمة اللحظية بنجاح',
                'data' => $customService,
                'base_total' => (float) $booking->total_price,
                'custom_total' => (float) $booking->customServices->sum('price'),
                'grand_total' => $booking->grand_total,
                'remaining' => $booking->remaining,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✅ تحديث خدمة لحظية (بدون تعديل total_price)
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0.01',
            ]);

            $customService = CustomService::find($id);
            if (!$customService) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ الخدمة غير موجودة',
                ], 404);
            }

            $booking = $customService->booking;
            
            // ✅ تحديث الخدمة فقط
            $customService->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
            ]);

            // ✅ إعادة تحميل العلاقة للحصول على القيم الجديدة
            $booking->load('customServices');

            return response()->json([
                'success' => true,
                'message' => '✅ تم تحديث الخدمة اللحظية بنجاح',
                'data' => $customService,
                'base_total' => (float) $booking->total_price,
                'custom_total' => (float) $booking->customServices->sum('price'),
                'grand_total' => $booking->grand_total,
                'remaining' => $booking->remaining,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✅ حذف خدمة لحظية (بدون تعديل total_price)
     */
    public function destroy($id)
    {
        try {
            $customService = CustomService::find($id);
            if (!$customService) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ الخدمة غير موجودة',
                ], 404);
            }

            $booking = $customService->booking;
            
            // ✅ حذف الخدمة فقط
            $customService->delete();

            // ✅ إعادة تحميل العلاقة للحصول على القيم الجديدة
            $booking->load('customServices');

            return response()->json([
                'success' => true,
                'message' => '✅ تم حذف الخدمة اللحظية بنجاح',
                'base_total' => (float) $booking->total_price,
                'custom_total' => (float) $booking->customServices->sum('price'),
                'grand_total' => $booking->grand_total,
                'remaining' => $booking->remaining,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على إجمالي الخدمات اللحظية لحجز معين
     */
    public function total($bookingId)
    {
        try {
            $booking = Booking::with('customServices')->findOrFail($bookingId);

            return response()->json([
                'success' => true,
                'booking_id' => $bookingId,
                'base_total' => (float) $booking->total_price,
                'custom_total' => (float) $booking->customServices->sum('price'),
                'grand_total' => $booking->grand_total,
                'remaining' => $booking->remaining,
                'custom_services_count' => $booking->customServices->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * البحث عن العملاء والحجوزات
     */
    public function search(Request $request)
{
    try {
        $query = $request->get('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $bookings = Booking::with(['service', 'customServices'])
            ->where(function($q) use ($query) {
                $q->where('customer_name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'customer_name' => $booking->customer_name,
                    'phone' => $booking->phone,
                    'service_name' => $booking->service->title_ar ?? '—',
                    'service_price' => (float) $booking->total_price,
                    'custom_services_total' => (float) $booking->customServices->sum('price'),
                    'grand_total' => $booking->grand_total,
                    'status' => $booking->status,
                    'date' => $booking->date->format('Y-m-d'),
                    'time' => $booking->time,
                ];
            }),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '❌ حدث خطأ: ' . $e->getMessage(),
        ], 500);
    }
}
}