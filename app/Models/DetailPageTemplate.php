<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'config_data',
        'preview_image',
        'is_active'
    ];

    protected $casts = [
        'config_data' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Scope to get active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get templates by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get spa templates
     */
    public static function getSpaTemplates()
    {
        return self::active()->byType('spa')->get();
    }

    /**
     * Get yoga templates
     */
    public static function getYogaTemplates()
    {
        return self::active()->byType('yoga')->get();
    }
}
