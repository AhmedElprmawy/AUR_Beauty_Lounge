<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * عرض صفحة الحجز (اختياري)
     */
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        $staff = Staff::where('is_active', true)->get();
        
        return view('frontend.bookings.index', compact('services', 'staff'));
    }

    /**
     * حفظ حجز جديد من العميل
     */
    public function store(Request $request)
    {
        try {
            // ✅ التحقق من البيانات
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'service_id' => 'required|exists:services,id',
                'staff_id' => 'nullable|exists:staff,id',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|date_format:H:i',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ إعداد البيانات للحجز
            $service = Service::find($request->service_id);
            
            $bookingData = [
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'service_id' => $request->service_id,
                'staff_id' => $request->staff_id,
                'date' => $request->date,
                'time' => $request->time,
                'notes' => $request->notes,
               'total_price' => (float) ($request->input('total_price', 0)),
                'deposit' => 0, // لا يوجد عربون في الحجز المباشر
                'status' => 'pending', // الحالة الافتراضية
            ];

            // ✅ إنشاء الحجز
            $booking = Booking::create($bookingData);

            // ✅ تسجيل الحدث
            Log::info('تم إنشاء حجز جديد من العميل', [
                'booking_id' => $booking->id,
                'customer' => $booking->customer_name,
                'phone' => $booking->phone
            ]);

            // ✅ إرسال إشعار (اختياري)
            // $this->sendNotification($booking);

            // ✅ الرد على الطلب
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ تم إنشاء حجزك بنجاح! سنتواصل معك قريباً لتأكيد الحجز.',
                    'data' => $booking
                ], 201);
            }

            return redirect()->route('home')
                ->with('success', '✅ تم إنشاء حجزك بنجاح! سنتواصل معك قريباً لتأكيد الحجز.');

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء الحجز من العميل: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ في إنشاء الحجز. يرجى المحاولة مرة أخرى.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', '❌ حدث خطأ في إنشاء الحجز. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل الحجز (للعميل)
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['service', 'staff'])->findOrFail($id);
            return view('frontend.bookings.show', compact('booking'));
        } catch (\Exception $e) {
            abort(404, 'الحجز غير موجود');
        }
    }

    /**
     * إلغاء الحجز (للعميل)
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status === 'cancelled') {
                return redirect()->back()->with('info', '⚠️ هذا الحجز ملغي بالفعل.');
            }

            if ($booking->status === 'completed') {
                return redirect()->back()->with('error', '❌ لا يمكن إلغاء حجز مكتمل.');
            }

            $booking->update(['status' => 'cancelled']);
            
            Log::info('تم إلغاء الحجز من العميل', ['booking_id' => $booking->id]);
            
            return redirect()->back()->with('success', '✅ تم إلغاء الحجز بنجاح.');
        } catch (\Exception $e) {
            Log::error('خطأ في إلغاء الحجز: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ حدث خطأ في إلغاء الحجز.');
        }
    }

    /**
     * إرسال إشعار (اختياري)
     */
    private function sendNotification($booking)
    {
        // يمكنك إضافة إرسال بريد إلكتروني أو SMS هنا
        // Mail::to($booking->email)->send(new BookingConfirmation($booking));
        // أو إرسال SMS
        // Sms::send($booking->phone, 'تم استلام حجزك بنجاح');
    }
}