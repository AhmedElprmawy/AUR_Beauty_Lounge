<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'category',
        'before_image',
        'after_image',
        'description_ar',
        'description_en',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // ✅ القيم الافتراضية
    protected $attributes = [
        'description_ar' => 'تحول مذهل في المظهر',
        'description_en' => 'Amazing transformation',
        'order' => 0,
        'is_active' => true
    ];

    public function getBeforeImageUrlAttribute()
    {
        if ($this->before_image) {
            return asset('storage/' . $this->before_image);
        }
        return asset('images/default-before.jpg');
    }

    public function getAfterImageUrlAttribute()
    {
        if ($this->after_image) {
            return asset('storage/' . $this->after_image);
        }
        return asset('images/default-after.jpg');
    }
}