<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'label',
        'icon',
        'image',
        'image_path',
        'features_ar',
        'features_en',
        'order',
        'is_active'
    ];

    protected $casts = [
        'features_ar' => 'array',
        'features_en' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * ✅ الحصول على رابط الصورة (مع صورة افتراضية)
     */
    public function getImageUrlAttribute()
    {
        // 1. إذا كانت الصورة مرفوعة من الجهاز
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        // 2. إذا كان رابط خارجي
        if ($this->image) {
            return $this->image;
        }
        
        // 3. الصورة الافتراضية
        return asset('images/default-service.jpg');
    }
}