<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class BookingReminderMessageService
{
    protected $centerName;

    public function __construct()
    {
        $this->centerName = config('app.name', 'AUR Beauty Lounge');
    }

    /**
     * رسالة تذكير 7 أيام للعميل
     */
    public function sevenDaysCustomerMessage(Booking $booking)
    {
        $data = $this->prepareBookingData($booking);
        
        return "مرحباً أ/ {$data['customer_name']} 👋

نذكركم بموعدكم القادم في {$this->centerName} ✨

الخدمة:
{$data['service_name']}

" . $this->formatAdditionalServices($data) . "
موعد الخدمة:
{$data['date']}
الساعة {$data['time']}

الموظف:
{$data['staff_name']}

إجمالي الحجز:
{$data['grand_total']} جنيه

العربون:
{$data['deposit']} جنيه

المتبقي:
{$data['remaining']} جنيه

يسعدنا استقبالكم ونتمنى لكم تجربة رائعة 🤍

{$this->centerName}";
    }

    /**
     * رسالة تذكير يوم واحد للعميل
     */
    public function oneDayCustomerMessage(Booking $booking)
    {
        $data = $this->prepareBookingData($booking);
        
        return "مرحباً أ/ {$data['customer_name']} 👋

تذكير من {$this->centerName} ✨

غداً موعد خدمتك:

الخدمة: {$data['service_name']}
التاريخ: {$data['date']}
الوقت: {$data['time']}
الموظف: {$data['staff_name']}

ننتظركم 🤍

{$this->centerName}";
    }

    /**
     * رسالة تذكير يوم واحد لصاحب العمل
     */
    public function oneDayOwnerMessage(Booking $booking)
    {
        $data = $this->prepareBookingData($booking);
        
        return "🔔 تذكير بحجز غداً

العميل:
{$data['customer_name']}

الهاتف:
{$data['customer_phone']}

الخدمة:
{$data['service_name']}

" . $this->formatAdditionalServices($data) . "
" . $this->formatCustomServices($data) . "
التاريخ:
{$data['date']}

الوقت:
{$data['time']}

الموظف:
{$data['staff_name']}

إجمالي الحجز:
{$data['grand_total']} جنيه

العربون:
{$data['deposit']} جنيه

المتبقي:
{$data['remaining']} جنيه

⚠️ يرجى تجهيز كل ما يلزم للحجز قبل الموعد.

{$this->centerName}";
    }

    /**
     * تجهيز بيانات الحجز
     */
    protected function prepareBookingData(Booking $booking)
    {
        $basePrice = (float) ($booking->total_price ?? 0);
        $customTotal = (float) $booking->customServices->sum('price');
        $grandTotal = $basePrice + $customTotal;
        $deposit = (float) ($booking->deposit ?? 0);
        $remaining = $grandTotal - $deposit;

        return [
            'customer_name' => $booking->customer_name ?? 'العميل',
            'customer_phone' => $booking->phone ?? '—',
            'service_name' => $booking->service->title_ar ?? '—',
            'service_price' => number_format($basePrice, 2),
            'custom_services' => $booking->customServices,
            'custom_total' => number_format($customTotal, 2),
            'grand_total' => number_format($grandTotal, 2),
            'deposit' => number_format($deposit, 2),
            'remaining' => number_format($remaining, 2),
            'date' => $booking->date ? $booking->date->format('l d F Y') : '—',
            'time' => $booking->time ?? '—',
            'staff_name' => $booking->staff->name_ar ?? '—',
        ];
    }

    /**
     * تنسيق الخدمات الإضافية
     */
    protected function formatAdditionalServices($data)
    {
        if ($data['custom_services']->count() > 0) {
            $services = '';
            foreach ($data['custom_services'] as $service) {
                $services .= "• {$service->name}: " . number_format($service->price, 2) . " جنيه\n";
            }
            $services .= "إجمالي الخدمات الإضافية: {$data['custom_total']} جنيه\n";
            return $services;
        }
        return '';
    }

    /**
     * تنسيق الخدمات اللحظية لصاحب العمل
     */
    protected function formatCustomServices($data)
    {
        if ($data['custom_services']->count() > 0) {
            $services = "الخدمات اللحظية:\n";
            foreach ($data['custom_services'] as $service) {
                $services .= "• {$service->name} - " . number_format($service->price, 2) . " جنيه\n";
            }
            return $services;
        }
        return '';
    }
}