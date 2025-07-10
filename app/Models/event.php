<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'nama',
        'deskripsi',
        'tanggal',
        'alamat',
        'harga',
        'noHP',
        'image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $dates = ['tanggal'];
    protected $casts = [
        'tanggal' => 'datetime',
        'harga' => 'decimal:2',
    ];
}
