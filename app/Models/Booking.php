<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'service_id',
        'staff_id',
        'date',
        'time',
        'notes',
        'status',
        'total_price',
        'deposit',
    ];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    // ===== العلاقات =====
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function additionalServices()
    {
        return $this->hasMany(BookingService::class);
    }

    /**
     * العلاقة مع الخدمات اللحظية
     */
    public function customServices()
    {
        return $this->hasMany(CustomService::class);
    }

    // ===== الحسابات الديناميكية =====

    /**
     * ✅ حساب إجمالي الخدمات اللحظية
     */
    public function getCustomServicesTotalAttribute()
    {
        return $this->customServices->sum('price');
    }

    /**
     * ✅ حساب الإجمالي النهائي (السعر الأساسي + الخدمات اللحظية)
     */
    public function getGrandTotalAttribute()
    {
        $basePrice = (float) ($this->total_price ?? 0);
        $customTotal = (float) $this->customServices->sum('price');
        return $basePrice + $customTotal;
    }

    /**
     * ✅ حساب المتبقي (الإجمالي النهائي - العربون)
     */
    public function getRemainingAttribute()
    {
        $deposit = (float) ($this->deposit ?? 0);
        return $this->grand_total - $deposit;
    }
     public function reminders()
    {
        return $this->hasMany(BookingReminder::class);
    }

    /**
     * التحقق من وجود تذكير معين
     */
    public function hasReminder($type, $recipientType)
    {
        return $this->reminders()
            ->where('type', $type)
            ->where('recipient_type', $recipientType)
            ->exists();
    }

    /**
     * الحصول على تذكير معين
     */
    public function getReminder($type, $recipientType)
    {
        return $this->reminders()
            ->where('type', $type)
            ->where('recipient_type', $recipientType)
            ->first();
    }
    public static function getPendingCount()
    {
        try {
            // ده هيعتبر أي حجز حالته مش "مكتمل" ومش "ملغي" بيعتبر معلق
            return self::query()
                ->whereNotIn('status', ['completed', 'cancelled', 'مكتمل', 'ملغي'])
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
