<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Jobs\SendBookingReminderJob;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * إرسال تذكير يدوي
     */
    public function send(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'type' => 'required|in:seven_days,one_day_customer,one_day_owner',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $type = $request->type;

        // ✅ التحقق من عدم التكرار
        $recipientType = $type === 'one_day_owner' ? 'owner' : 'customer';
        if ($booking->hasReminder($type, $recipientType)) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ هذا التذكير تم إرساله بالفعل',
            ], 400);
        }

        // ✅ إرسال التذكير
        dispatch(new SendBookingReminderJob($booking, $type, false))
            ->onQueue('reminders');

        return response()->json([
            'success' => true,
            'message' => '✅ تم إرسال التذكير بنجاح',
        ]);
    }

    /**
     * إعادة إرسال تذكير فاشل
     */
    public function retry(Request $request)
    {
        $request->validate([
            'reminder_id' => 'required|exists:booking_reminders,id',
        ]);

        $reminder = \App\Models\BookingReminder::findOrFail($request->reminder_id);
        $booking = $reminder->booking;

        // ✅ إرسال التذكير مرة أخرى
        dispatch(new SendBookingReminderJob($booking, $reminder->type, false))
            ->onQueue('reminders');

        return response()->json([
            'success' => true,
            'message' => '✅ تم إعادة إرسال التذكير',
        ]);
    }
}