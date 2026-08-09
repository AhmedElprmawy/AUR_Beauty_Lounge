<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\BookingReminder;
use App\Services\BookingReminderMessageService;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBookingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    protected $reminderType;
    protected $isTest;

    public function __construct(Booking $booking, $reminderType, $isTest = false)
    {
        $this->booking = $booking;
        $this->reminderType = $reminderType;
        $this->isTest = $isTest;
    }

    public function handle(
        WhatsAppService $whatsAppService,
        BookingReminderMessageService $messageService
    ) {
        try {
            // تحديد نوع التذكير والمستلم
            $reminderConfig = $this->getReminderConfig();

            if (!$reminderConfig) {
                Log::warning('⚠️ Unknown reminder type: ' . $this->reminderType);
                return;
            }

            $recipientType = $reminderConfig['recipient_type'];
            $recipientPhone = $reminderConfig['recipient_phone'];
            $messageKey = $reminderConfig['message_key'];

            // ✅ التحقق من وجود التذكير
            if ($this->booking->hasReminder($this->reminderType, $recipientType)) {
                Log::info('ℹ️ Reminder already sent for booking ' . $this->booking->id, [
                    'type' => $this->reminderType,
                    'recipient' => $recipientType,
                ]);
                return;
            }

            // ✅ بناء الرسالة
            $message = $this->buildMessage($messageService, $messageKey);

            // ✅ إرسال الرسالة
            $result = $whatsAppService->sendMessage($recipientPhone, $message, $this->isTest);

            // ✅ تسجيل التذكير
            $reminder = $this->createReminder($recipientType, $recipientPhone, $message);

            if ($result['success']) {
                $reminder->markAsSent();
                Log::info('✅ Reminder sent successfully', [
                    'booking_id' => $this->booking->id,
                    'type' => $this->reminderType,
                    'recipient' => $recipientType,
                ]);
            } else {
                $errorMessage = $result['error'] ?? 'Unknown error';
                $reminder->markAsFailed($errorMessage);
                Log::error('❌ Failed to send reminder', [
                    'booking_id' => $this->booking->id,
                    'type' => $this->reminderType,
                    'error' => $errorMessage,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('❌ SendBookingReminderJob exception', [
                'booking_id' => $this->booking->id,
                'type' => $this->reminderType,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * الحصول على إعدادات التذكير
     */
    protected function getReminderConfig()
    {
        $configs = [
            'seven_days' => [
                'recipient_type' => 'customer',
                'recipient_phone' => $this->booking->phone,
                'message_key' => 'sevenDaysCustomerMessage',
            ],
            'one_day_customer' => [
                'recipient_type' => 'customer',
                'recipient_phone' => $this->booking->phone,
                'message_key' => 'oneDayCustomerMessage',
            ],
            'one_day_owner' => [
                'recipient_type' => 'owner',
                'recipient_phone' => config('services.whatsapp.owner_number'),
                'message_key' => 'oneDayOwnerMessage',
            ],
        ];

        return $configs[$this->reminderType] ?? null;
    }

    /**
     * بناء الرسالة
     */
    protected function buildMessage($messageService, $messageKey)
    {
        $method = $messageKey;
        if (method_exists($messageService, $method)) {
            return $messageService->$method($this->booking);
        }

        Log::warning('⚠️ Message method not found: ' . $method);
        return 'تذكير بموعد الخدمة في ' . config('app.name');
    }

    /**
     * إنشاء سجل التذكير
     */
    protected function createReminder($recipientType, $recipientPhone, $message)
    {
        return BookingReminder::create([
            'booking_id' => $this->booking->id,
            'type' => $this->reminderType,
            'recipient_type' => $recipientType,
            'recipient_phone' => $recipientPhone,
            'scheduled_at' => now(),
            'status' => 'pending',
            'message' => $message,
        ]);
    }
}