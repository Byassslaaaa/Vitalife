<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'image', 
        'description', 
        'discount_percentage',  
        'usage_count',
        'usage_limit',
        'is_used',
        'expired_at', 
        'code'
    ];
    
    // Make sure dates are properly cast
    protected $casts = [
        'expired_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}
