<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

// class WeatherService
// {
//     public function getWeatherData(string $city)
//     {
//         return Cache::remember('weather_data_' . $city, 1800, function () use ($city) {
//             $apiKey = config('services.openweathermap.key');
//             $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
//                 'q' => $city,
//                 'appid' => $apiKey,
//                 'units' => 'metric'
//             ]);

//             if ($response->successful()) {
//                 $data = $response->json();
//                 return [
//                     'temperature' => $data['main']['temp'],
//                     'description' => $data['weather'][0]['description'],
//                 ];
//             }

//             return null;
//         });
//     }
// }
