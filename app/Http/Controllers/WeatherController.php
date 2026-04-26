<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function view(Request $request)
    {
        $region = $request->input('region', 'Maharashtra');

        // Coordinate Mapping for Regions
        $coordinates = [
            'Maharashtra' => ['lat' => 19.75, 'lng' => 75.71],
            'Punjab' => ['lat' => 31.14, 'lng' => 75.34],
            'Uttar Pradesh' => ['lat' => 26.84, 'lng' => 80.94],
            'Madhya Pradesh' => ['lat' => 22.97, 'lng' => 78.65],
            'Karnataka' => ['lat' => 15.31, 'lng' => 75.71],
        ];

        $coords = $coordinates[$region] ?? $coordinates['Maharashtra'];

        // Fetch Real-time Weather from Open-Meteo API
        try {
            $weatherResponse = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $coords['lat'],
                'longitude' => $coords['lng'],
                'current_weather' => 'true',
                'hourly' => 'relative_humidity_2m,precipitation_probability',
                'timezone' => 'auto'
            ]);

            if ($weatherResponse->successful()) {
                $weatherData = $weatherResponse->json();
                $temp = $weatherData['current_weather']['temperature'] ?? 30;
                $windSpeed = $weatherData['current_weather']['windspeed'] ?? 10;
                $humidity = $weatherData['hourly']['relative_humidity_2m'][0] ?? 60;
                $rainChance = $weatherData['hourly']['precipitation_probability'][0] ?? 10;
            } else {
                throw new \Exception('Weather API failed');
            }
        } catch (\Exception $e) {
            $temp = rand(22, 38);
            $humidity = rand(40, 90);
            $rainChance = rand(0, 100);
            $windSpeed = rand(5, 20);
        }

        $prompt = "Region: {$region}. Weather: {$temp}°C, humidity {$humidity}%, wind speed {$windSpeed} km/h, rain chance {$rainChance}%. Give concise farming advice based on these exact current conditions.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        return response()->json([
            'temp' => $temp,
            'humidity' => $humidity,
            'rain_chance' => $rainChance,
            'wind_speed' => $windSpeed,
            'region' => $region,
            'coords' => $coords,
            'advice' => $aiResponse
        ]);
    }
}
