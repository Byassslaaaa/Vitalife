<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableTime extends Model
{
    protected $fillable = [
        'spa_id',
        'time_slot',
        'available',
    ];
    use HasFactory;
}
