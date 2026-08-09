<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingEditAccessController extends Controller
{
    /**
     * عرض صفحة إدخال كلمة المرور
     *
     * Editing is authorized via the authenticated admin user's edit password
     * (users.edit_password). Per-booking passwords are no longer created by this controller.
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();

        if (! $user) {
            return view('admin.bookings.edit-password', compact('booking'))
                ->with('error', '❌ يجب تسجيل الدخول أولاً كمسؤول لتعديل الحجوزات');
        }

        if (! $user->hasEditPassword()) {
            return view('admin.bookings.edit-password', compact('booking'))
                ->with('error', '❌ لم يتم تعيين كلمة مرور تعديل للحساب. يرجى التواصل مع المدير.');
        }

        // ✅ إزالة أي Session سابقة عند فتح الصفحة (لأمان أعلى)
        Session::forget('booking_edit_verified_' . $booking->id);
        Session::forget('booking_edit_verified_at_' . $booking->id);

        return view('admin.bookings.edit-password', compact('booking'));
    }

    /**
     * التحقق من كلمة المرور
     */
    public function verify(Request $request, Booking $booking)
    {
        $request->validate([
            'password' => 'required|string|min:4'
        ]);

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('admin.bookings.edit', $booking)
                ->with('error', '❌ يجب تسجيل الدخول أولاً');
        }

        if (! $user->hasEditPassword()) {
            return redirect()->route('admin.bookings.edit', $booking)
                ->with('error', '❌ لم يتم تعيين كلمة مرور تعديل للحساب. يرجى التواصل مع المدير.');
        }

        if (! $user->verifyEditPassword($request->password)) {
            return redirect()->route('admin.bookings.edit', $booking)
                ->with('error', '❌ كلمة المرور غير صحيحة');
        }

        // ✅ تخزين في Session أنه تم التحقق (booking-scoped)
        Session::put('booking_edit_verified_' . $booking->id, true);
        Session::put('booking_edit_verified_at_' . $booking->id, now());

        // ✅ التوجيه إلى صفحة التعديل الفعلية (admin.bookings.edit.form)
        return redirect()->route('admin.bookings.edit.form', $booking)
            ->with('success', '✅ تم التحقق من كلمة المرور بنجاح. يمكنك الآن تعديل الحجز.');
    }

    /**
     * تسجيل الخروج من جلسة التعديل
     */
    public function logout(Request $request)
    {
        $bookingId = $request->booking_id;
        
        if ($bookingId) {
            Session::forget('booking_edit_verified_' . $bookingId);
            Session::forget('booking_edit_verified_at_' . $bookingId);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', '✅ تم تسجيل الخروج من جلسة التعديل');
    }

    /**
     * إعادة تعيين كلمة المرور
     *
     * Disabled: edit-passwords are managed on admin user accounts now.
     */
    public function resetPassword(Booking $booking)
    {
        return response()->json([
            'success' => false,
            'message' => '🚫 إعادة التعيين غير متاحة. يرجى إعادة تعيين كلمة مرور التعديل من إعدادات حساب المشرف.'
        ], 403);
    }

    /**
     * إرسال كلمة المرور (اختياري)
     */
    private function sendEditPassword($booking, $password)
    {
        // يمكن إرسال كلمة المرور عبر البريد الإلكتروني
        // Mail::to($booking->email)->send(new EditPasswordMail($booking, $password));
        
        // أو عبر SMS
        // Sms::send($booking->phone, 'كلمة مرور تعديل الحجز: ' . $password);
    }
}