<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YogaService extends Model
{
    use HasFactory;

    protected $fillable = [
        'yoga_id',
        'name',
        'description',
        'price',
        'duration',
        'category',
        'difficulty_level',
        'max_participants',
        'benefits',
        'requirements',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Relationship with Yoga model
     */
    public function yoga()
    {
        return $this->belongsTo(Yoga::class, 'yoga_id', 'id_yoga');
    }

    /**
     * Relationship with YogaBooking model
     */
    public function yogaBookings()
    {
        return $this->hasMany(YogaBooking::class, 'service_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
