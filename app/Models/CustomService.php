<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomService extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'name',
        'price',
        'staff_id',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}