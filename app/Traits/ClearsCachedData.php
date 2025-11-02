<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait ClearsCachedData
{
    /**
     * Clear all spa-related caches
     */
    protected function clearSpaCache(): void
    {
        $keys = [
            'spas.all',
            'spas.active',
            'spa.services.*',
        ];

        foreach ($keys as $key) {
            if (str_contains($key, '*')) {
                // For wildcard patterns, we'd need to implement cache tagging
                // For now, just log it
                Log::info('Cache pattern to clear: ' . $key);
            } else {
                Cache::forget($key);
                Log::info('Cache cleared: ' . $key);
            }
        }
    }

    /**
     * Clear all yoga-related caches
     */
    protected function clearYogaCache(): void
    {
        $keys = [
            'yogas.all',
            'yogas.active',
            'yoga.services.*',
        ];

        foreach ($keys as $key) {
            if (!str_contains($key, '*')) {
                Cache::forget($key);
                Log::info('Cache cleared: ' . $key);
            }
        }
    }

    /**
     * Clear all gym-related caches
     */
    protected function clearGymCache(): void
    {
        $keys = [
            'gyms.all',
            'gyms.active',
            'gym.services.*',
        ];

        foreach ($keys as $key) {
            if (!str_contains($key, '*')) {
                Cache::forget($key);
                Log::info('Cache cleared: ' . $key);
            }
        }
    }

    /**
     * Clear booking statistics cache
     */
    protected function clearBookingStatsCache(): void
    {
        Cache::forget('booking.stats.spa');
        Cache::forget('booking.stats.yoga');
        Cache::forget('booking.stats.gym');
        Cache::forget('booking.stats.all');

        Log::info('Booking stats cache cleared');
    }

    /**
     * Clear all application caches
     */
    protected function clearAllCache(): void
    {
        Cache::flush();
        Log::info('All application cache cleared');
    }

    /**
     * Remember cached data with automatic clearing on model events
     *
     * @param string $key Cache key
     * @param int $ttl Time to live in seconds
     * @param \Closure $callback Data retrieval callback
     * @return mixed
     */
    protected function rememberCached(string $key, int $ttl, \Closure $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Clear specific cache key
     *
     * @param string $key
     */
    protected function clearCache(string $key): void
    {
        Cache::forget($key);
        Log::info('Cache cleared: ' . $key);
    }

    /**
     * Clear multiple cache keys
     *
     * @param array $keys
     */
    protected function clearCaches(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Log::info('Multiple caches cleared: ' . implode(', ', $keys));
    }
}
