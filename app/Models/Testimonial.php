<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'role_ar',
        'role_en',
        'client_role_ar',
        'client_role_en',
        'content_ar',
        'content_en',
        'rating',
        'avatar',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer'
    ];

    // ✅ القيم الافتراضية
    protected $attributes = [
        'is_active' => true,
        'rating' => 5
    ];

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }
        return asset('images/default-avatar.jpg');
    }

    public function getRoleArAttribute($value)
    {
        return $this->attributes['client_role_ar'] ?? $value;
    }

    public function setRoleArAttribute($value)
    {
        $this->attributes['client_role_ar'] = $value;
    }

    public function getRoleEnAttribute($value)
    {
        return $this->attributes['client_role_en'] ?? $value;
    }

    public function setRoleEnAttribute($value)
    {
        $this->attributes['client_role_en'] = $value;
    }
}