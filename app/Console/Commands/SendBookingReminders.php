<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Jobs\SendBookingReminderJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBookingReminders extends Command
{
    protected $signature = 'reminders:send {--booking= : Send reminder for specific booking ID} {--test : Test mode without actually sending WhatsApp}';
    protected $description = 'Send booking reminders to customers and owner';

    public function handle()
    {
        $this->info('🔄 Starting booking reminders...');

        $bookingId = $this->option('booking');
        $isTest = $this->option('test');

        if ($bookingId) {
            $this->sendForBooking($bookingId, $isTest);
            return;
        }

        // ✅ الحجوزات التي تحتاج تذكير 7 أيام
        $this->sendSevenDaysReminders($isTest);

        // ✅ الحجوزات التي تحتاج تذكير يوم واحد
        $this->sendOneDayReminders($isTest);

        $this->info('✅ Reminders process completed.');
    }

    /**
     * إرسال تذكير 7 أيام
     */
    protected function sendSevenDaysReminders($isTest)
    {
        $targetDate = Carbon::now()->addDays(7)->startOfDay();

        $bookings = Booking::with(['service', 'staff', 'customServices'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('date', $targetDate->toDateString())
            ->get();

        $this->info("📅 Found {$bookings->count()} bookings for 7-day reminder");

        foreach ($bookings as $booking) {
            $this->sendReminder($booking, 'seven_days', $isTest);
        }
    }

    /**
     * إرسال تذكير يوم واحد
     */
    protected function sendOneDayReminders($isTest)
    {
        $targetDate = Carbon::now()->addDay()->startOfDay();

        $bookings = Booking::with(['service', 'staff', 'customServices'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('date', $targetDate->toDateString())
            ->get();

        $this->info("📅 Found {$bookings->count()} bookings for 1-day reminder");

        foreach ($bookings as $booking) {
            // ✅ تذكير العميل
            $this->sendReminder($booking, 'one_day_customer', $isTest);

            // ✅ تذكير صاحب العمل
            $this->sendReminder($booking, 'one_day_owner', $isTest);
        }
    }

    /**
     * إرسال تذكير لحجز معين
     */
    protected function sendReminder($booking, $type, $isTest)
    {
        // ✅ التحقق من عدم تكرار التذكير
        if ($booking->hasReminder($type, $this->getRecipientType($type))) {
            $this->warn("⚠️ Reminder already sent for booking #{$booking->id} ({$type})");
            return;
        }

        $this->info("📤 Dispatching reminder for booking #{$booking->id} ({$type})");

        dispatch(new SendBookingReminderJob($booking, $type, $isTest))
            ->onQueue('reminders');

        $this->info("✅ Reminder dispatched for booking #{$booking->id} ({$type})");
    }

    /**
     * إرسال تذكير لحجز محدد (للاختبار)
     */
    protected function sendForBooking($bookingId, $isTest)
    {
        $booking = Booking::with(['service', 'staff', 'customServices'])
            ->find($bookingId);

        if (!$booking) {
            $this->error("❌ Booking #{$bookingId} not found.");
            return;
        }

        $this->info("📤 Sending reminders for booking #{$booking->id}");

        $types = ['seven_days', 'one_day_customer', 'one_day_owner'];

        foreach ($types as $type) {
            if (!$booking->hasReminder($type, $this->getRecipientType($type))) {
                $this->sendReminder($booking, $type, $isTest);
            } else {
                $this->warn("⚠️ Reminder already sent for booking #{$booking->id} ({$type})");
            }
        }

        $this->info("✅ Done for booking #{$booking->id}");
    }

    /**
     * الحصول على نوع المستلم حسب نوع التذكير
     */
    protected function getRecipientType($type)
    {
        if ($type === 'one_day_owner') {
            return 'owner';
        }
        return 'customer';
    }
}