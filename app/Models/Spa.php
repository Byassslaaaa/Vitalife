<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Spa extends Model
{
    use HasFactory;

    protected $table = 'spas';
    protected $primaryKey = 'id_spa';

    protected $fillable = [
        'nama',
        'alamat',
        'noHP',
        'image',
        'maps',
        'waktuBuka',
        'services',
        'is_open'
    ];

    protected $casts = [
        'waktuBuka' => 'array',
        'services' => 'array',
        'is_open' => 'boolean',
    ];

    /**
     * Get the spa services for the spa (relationship)
     */
    public function spaServices()
    {
        return $this->hasMany(SpaService::class, 'spa_id', 'id_spa');
    }

    /**
     * Alias for spaServices to match the error
     * This creates a relationship called 'services'
     */
    public function services()
    {
        return $this->hasMany(SpaService::class, 'spa_id', 'id_spa');
    }

    /**
     * Get the active spa services for the spa
     */
    public function activeSpaServices()
    {
        return $this->hasMany(SpaService::class, 'spa_id', 'id_spa')->where('is_active', true);
    }

    /**
     * Get the spa detail configuration
     */
    public function spaDetail()
    {
        return $this->hasOne(SpaDetail::class, 'spa_id', 'id_spa');
    }

    /**
     * Alias for spaDetail for backward compatibility
     */
    public function detailConfig()
    {
        return $this->hasOne(SpaDetail::class, 'spa_id', 'id_spa');
    }

    /**
     * Get the spa bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'spa_id', 'id_spa');
    }

    /**
     * Get JSON services as attribute (different from relationship)
     */
    public function getJsonServicesAttribute()
    {
        return $this->services; // This accesses the JSON column
    }

    /**
     * Check if spa is currently open based on schedule
     */
    public function getIsCurrentlyOpenAttribute()
    {
        if (!$this->is_open) {
            return false;
        }

        $now = Carbon::now();
        $currentDay = $now->format('l'); // Full day name (Monday, Tuesday, etc.)
        
        // Map English day names to Indonesian
        $dayMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $indonesianDay = $dayMapping[$currentDay] ?? $currentDay;
        
        if (!$this->waktuBuka || !isset($this->waktuBuka[$indonesianDay])) {
            return false;
        }

        $todayHours = $this->waktuBuka[$indonesianDay];
        
        // Parse opening hours (assuming format like "08:00-20:00")
        if (preg_match('/(\d{1,2}):(\d{2})-(\d{1,2}):(\d{2})/', $todayHours, $matches)) {
            $openTime = sprintf('%02d:%02d', $matches[1], $matches[2]);
            $closeTime = sprintf('%02d:%02d', $matches[3], $matches[4]);
            
            $currentTime = $now->format('H:i');
            
            return $currentTime >= $openTime && $currentTime <= $closeTime;
        }

        return true; // Default to open if can't parse hours
    }

    /**
     * Get formatted opening hours for display
     */
    public function getFormattedOpeningHoursAttribute()
    {
        if (!$this->waktuBuka) {
            return [];
        }

        $formatted = [];
        foreach ($this->waktuBuka as $day => $hours) {
            $formatted[] = [
                'day' => $day,
                'hours' => $hours
            ];
        }

        return $formatted;
    }

    /**
     * Scope to get active spas
     */
    public function scopeActive($query)
    {
        return $query->where('is_open', true);
    }

    /**
     * Scope to search spas
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('alamat', 'like', '%' . $search . '%');
        });
    }
}
