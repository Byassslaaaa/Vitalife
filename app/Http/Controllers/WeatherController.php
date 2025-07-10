<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function getWeather(): JsonResponse
    {
        $apiKey = config('services.openweathermap.key');
        $city = 'Yogyakarta';

        $weatherData = Cache::remember('weather_data', 1800, function () use ($apiKey, $city) {
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temperature' => $data['main']['temp'],
                    'description' => $data['weather'][0]['description'],
                ];
            }

            return null;
        });

        if ($weatherData) {
            return response()->json($weatherData);
        }

        return response()->json(['error' => 'Unable to fetch weather data'], 500);
    }
}
