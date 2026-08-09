<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class CreateBookingNotifications extends Command
{
    protected $signature = 'notifications:create-booking-reminders';
    protected $description = 'إنشاء إشعارات تذكير للحجوزات قبل 24 ساعة';

    public function handle()
    {
        $this->info('🔄 بدء إنشاء إشعارات التذكير...');

        // ✅ جلب الحجوزات المؤكدة
        $bookings = Booking::where('status', 'confirmed')
            ->where('date', '>=', now())
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('✅ لا توجد حجوزات مؤكدة');
            return Command::SUCCESS;
        }

        // ✅ جلب المستخدمين الذين سيستلمون الإشعار (المدراء)
        $admins = User::where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            $this->error('❌ لا يوجد مدراء لإرسال الإشعارات لهم');
            return Command::FAILURE;
        }

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($bookings as $booking) {
            try {
                // ✅ التحقق من وجود إشعار مسبق لهذا الحجز
                $hasNotification = false;
                
                // ✅ التحقق من وجود إشعار بنفس booking_id في data
                foreach ($admins as $admin) {
                    $existingNotifications = $admin->notifications()
                        ->where('type', 'App\Notifications\BookingReminderNotification')
                        ->where('data->booking_id', $booking->id)
                        ->exists();
                    
                    if ($existingNotifications) {
                        $hasNotification = true;
                        break;
                    }
                }

                if ($hasNotification) {
                    $skippedCount++;
                    continue;
                }

                // ✅ إرسال الإشعار لكل مدير
                foreach ($admins as $admin) {
                    $admin->notify(new BookingReminderNotification($booking));
                }

                $createdCount++;
                $this->info("✅ تم إنشاء إشعار للحجز #{$booking->id}");

            } catch (\Exception $e) {
                $this->error("❌ فشل إنشاء إشعار للحجز #{$booking->id}: " . $e->getMessage());
                Log::error('فشل إنشاء إشعار', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("📊 التقرير: تم إنشاء {$createdCount} إشعار، تم تخطي {$skippedCount} حجز (يوجد إشعار مسبق)");
        
        return Command::SUCCESS;
    }
}