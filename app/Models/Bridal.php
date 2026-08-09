<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bridal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'image',
        'small_image',
        'stats_number',
        'stats_label_ar',
        'stats_label_en',
        'features_ar',
        'features_en',
        'is_active'
    ];

    protected $casts = [
        'features_ar' => 'array',
        'features_en' => 'array',
        'is_active' => 'boolean'
    ];
}