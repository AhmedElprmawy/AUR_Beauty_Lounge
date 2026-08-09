<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingReminderNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * ✅ تحديد قنوات الإرسال
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * ✅ بيانات الإشعار للتخزين في قاعدة البيانات
     */
    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'customer_name' => $this->booking->customer_name,
            'phone' => $this->booking->phone,
            'service_name' => $this->booking->service->title_ar ?? 'خدمة',
            'staff_name' => $this->booking->staff->name_ar ?? 'موظف',
            'date' => $this->booking->date ? $this->booking->date->format('Y-m-d') : null,
            'time' => $this->booking->time,
            'status' => $this->booking->status,
            'type' => 'booking_reminder',
            'message' => "🔔 تذكير بموعد خدمة\n" .
                         "👤 العميل: {$this->booking->customer_name}\n" .
                         "💇‍♀️ الخدمة: " . ($this->booking->service->title_ar ?? 'خدمة') . "\n" .
                         "📅 التاريخ: " . ($this->booking->date ? $this->booking->date->format('Y-m-d') : 'غير محدد') . "\n" .
                         "⏰ الوقت: {$this->booking->time}",
        ];
    }

    /**
     * ✅ بيانات الإشعار للـ Array
     */
    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'customer_name' => $this->booking->customer_name,
            'service_name' => $this->booking->service->title_ar ?? 'خدمة',
            'date' => $this->booking->date ? $this->booking->date->format('Y-m-d') : null,
            'time' => $this->booking->time,
            'type' => 'booking_reminder',
        ];
    }
}