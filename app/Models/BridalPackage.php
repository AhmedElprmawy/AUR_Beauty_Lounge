<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BridalPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'tier',
        'price',
        'currency',
        'features_ar',
        'features_en',
        'description_ar',
        'description_en',
        'is_popular',
        'order',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features_ar' => 'array',
        'features_en' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'order' => 0,
        'is_active' => true,
        'is_popular' => false,
        'currency' => 'SAR'
    ];
}