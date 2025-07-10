<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaService extends Model
{
    use HasFactory;

    protected $fillable = [
        'spa_id',
        'name',
        'description',
        'price',
        'duration',
        'category',
        'is_active'
    ];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Relationship with Spa model
     */
    public function spa()
    {
        return $this->belongsTo(Spa::class, 'spa_id', 'id_spa');
    }

    /**
     * Relationship with SpaBooking model
     */
    public function spaBookings()
    {
        return $this->hasMany(SpaBooking::class, 'service_id');
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
     * Scope for services by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get service categories
     */
    public static function getCategories()
    {
        return [
            'Massage' => 'Massage',
            'Facial' => 'Facial',
            'Body Treatment' => 'Body Treatment',
            'Aromatherapy' => 'Aromatherapy',
            'Reflexology' => 'Reflexology'
        ];
    }
}
