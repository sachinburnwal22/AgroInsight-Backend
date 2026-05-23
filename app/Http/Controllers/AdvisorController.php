<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdvisorController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    // 1. Update live location
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'current_region' => 'nullable|string'
        ]);

        $user = auth('sanctum')->user();
        if ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'current_region' => $request->current_region ?? $user->current_region,
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User location updated successfully.',
                'data' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'region' => $request->current_region
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Guest coordinates logged (not saved to database).',
            'data' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'region' => $request->current_region
            ]
        ]);
    }

    // 2. Fetch live weather info
    public function getLiveWeather(Request $request)
    {
        $lat = $request->query('latitude');
        $lng = $request->query('longitude');

        // Fallback to user saved location
        if (!$lat || !$lng) {
            $user = auth('sanctum')->user();
            if ($user && $user->latitude && $user->longitude) {
                $lat = $user->latitude;
                $lng = $user->longitude;
            } else {
                // Default coordinates (Pune/Maharashtra)
                $lat = 18.5204;
                $lng = 73.8567;
            }
        }

        try {
            $response = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $lat,
                'longitude' => $lng,
                'current_weather' => 'true',
                'hourly' => 'relative_humidity_2m,precipitation_probability,precipitation',
                'timezone' => 'auto'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'status' => 'success',
                    'temp' => $data['current_weather']['temperature'] ?? 28.0,
                    'wind_speed' => $data['current_weather']['windspeed'] ?? 12.0,
                    'humidity' => $data['hourly']['relative_humidity_2m'][0] ?? 60,
                    'rain_chance' => $data['hourly']['precipitation_probability'][0] ?? 10,
                    'precipitation' => $data['hourly']['precipitation'][0] ?? 0.0,
                    'latitude' => $lat,
                    'longitude' => $lng
                ]);
            }
            throw new \Exception('Weather provider API error');
        } catch (\Exception $e) {
            Log::error('Weather API failed', ['error' => $e->getMessage()]);
            // Return safe mock values for smooth running offline
            return response()->json([
                'status' => 'mock_success',
                'temp' => 31.5,
                'wind_speed' => 8.4,
                'humidity' => 52,
                'rain_chance' => 15,
                'precipitation' => 0.0,
                'latitude' => $lat,
                'longitude' => $lng
            ]);
        }
    }

    // 3. Fetch Extreme Weather Alerts & AI Disaster Prevention Guide
    public function getWeatherAlerts(Request $request)
    {
        $lat = $request->query('latitude', 18.5204);
        $lng = $request->query('longitude', 73.8567);
        $simulate = $request->query('simulate');
        $region = $request->query('region', 'Maharashtra');

        $temp = (float)$request->query('temp', 28.0);
        $windSpeed = (float)$request->query('wind_speed', 12.0);
        $humidity = (int)$request->query('humidity', 60);
        $rainChance = (int)$request->query('rain_chance', 10);

        $alertType = null;
        $severity = 'info';
        $message = '';

        if ($simulate) {
            if ($simulate === 'flood') {
                $alertType = 'Flood Warning';
                $severity = 'severe';
                $message = '⚠️ Extreme precipitation and rising water levels detected. Avoid low-lying fields and pause irrigation.';
            } else if ($simulate === 'cyclone') {
                $alertType = 'Cyclone Alert';
                $severity = 'severe';
                $message = '⚠️ Cyclone and storm winds exceeding 65km/h expected in the next 12 hours. Secure greenhouse structures and farming equipment.';
            } else if ($simulate === 'heatwave') {
                $alertType = 'Heatwave Warning';
                $severity = 'severe';
                $message = '⚠️ Extreme temperatures above 43°C detected. Soil moisture evaporating rapidly. Irrigate early morning or evening to reduce crop shock.';
            } else if ($simulate === 'drought') {
                $alertType = 'Drought Warning';
                $severity = 'moderate';
                $message = '⚠️ Prolonged dry spells and high evaporation rates detected. Implement mulch and drip irrigation systems.';
            }
        } else {
            if ($temp > 42) {
                $alertType = 'Heatwave Warning';
                $severity = 'severe';
                $message = '⚠️ Extreme heatwave detected: Temperature reached ' . $temp . '°C.';
            } else if ($windSpeed > 55) {
                $alertType = 'Cyclone/Storm Alert';
                $severity = 'severe';
                $message = '⚠️ Cyclone risk: Gale winds of ' . $windSpeed . ' km/h detected.';
            } else if ($rainChance > 85) {
                $alertType = 'Flood Warning';
                $severity = 'severe';
                $message = '⚠️ Flood Alert: High likelihood of flooding with ' . $rainChance . '% rain probability.';
            } else if ($temp > 35 && $humidity < 20) {
                $alertType = 'Drought Risk';
                $severity = 'moderate';
                $message = '⚠️ Drought Warning: Low relative humidity (' . $humidity . '%) and high temperatures.';
            }
        }

        $aiResponse = '';
        if ($alertType) {
            $prompt = "Location: {$region} (lat: {$lat}, lng: {$lng}). Weather: Temp {$temp}°C, Wind {$windSpeed} km/h, Humidity {$humidity}%, rain chance {$rainChance}%. Danger warning active: {$alertType} ({$message}). Explain in 2 bullet points the direct risk this poses to agricultural crops and list 3 concise, farmer-friendly preventive measures. Keep it short and actionable.";
            $aiResponse = $this->geminiService->generateContent($prompt);

            // Robust local fallback if Gemini API is rate-limited or quota is exceeded
            if (empty($aiResponse) || str_contains($aiResponse, 'AI Error:')) {
                $alertLower = strtolower($alertType);
                if (str_contains($alertLower, 'flood')) {
                    $aiResponse = "• Crop Risk: Causes root drowning and oxygen depletion, leading to immediate root rot.\n• Crop Risk: High-velocity runoff washes away essential nutrients and newly sown seeds.\n\nPreventive Measures:\n1. Dig or clear perimeter drainage channels to route excess runoff away from crop fields.\n2. Postpone any scheduled pesticide or fertilizer spraying operations to prevent runoff waste.\n3. Elevate stored harvest sacks and farm machinery to dry, high-ground platforms.";
                } else if (str_contains($alertLower, 'cyclone') || str_contains($alertLower, 'storm')) {
                    $aiResponse = "• Crop Risk: High-velocity winds cause crop lodging (stalk breakage) and structural collapse.\n• Crop Risk: Physical defoliation and severe branch breakage of fruit orchards.\n\nPreventive Measures:\n1. Secure greenhouse frame structures and tie down tall crops like sugarcane or banana stalks.\n2. Harvest all mature produce immediately to protect yield from high wind damage.\n3. Clean field waterways of debris to prevent flash blockages and soil pooling.";
                } else if (str_contains($alertLower, 'heatwave')) {
                    $aiResponse = "• Crop Risk: Rapid transpiration and severe soil dehydration causing premature wilting.\n• Crop Risk: Scorching of young leaves and sudden flower or fruit drop.\n\nPreventive Measures:\n1. Irrigate fields during early morning or evening hours to minimize water evaporation loss.\n2. Apply organic mulch cover (straw/hay) over root zones to conserve soil moisture.\n3. Install temporary green shade net barriers for nursery seedlings and sensitive greens.";
                } else { // Drought or general dry alert
                    $aiResponse = "• Crop Risk: Permanent wilting point acceleration and soil cracking damage.\n• Crop Risk: Nutrient immobilization due to lack of soil moisture transport medium.\n\nPreventive Measures:\n1. Transition to precise drip irrigation pipelines to optimize water delivery directly to roots.\n2. Maintain complete organic mulch cover on soil beds to seal in residual moisture.\n3. Suspend nitrogen-heavy fertilizing rounds, which increase plant water consumption stresses.";
                }
            }

            // Log Alert to database if authenticated
            $user = auth('sanctum')->user();
            if ($user) {
                DB::table('weather_alerts')->insert([
                    'user_id' => $user->id,
                    'alert_type' => $alertType,
                    'severity' => $severity,
                    'message' => $message . ' AI Guide: ' . substr($aiResponse, 0, 500),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'alert_active' => !empty($alertType),
            'alert_type' => $alertType,
            'severity' => $severity,
            'message' => $message,
            'ai_prevention_guide' => $aiResponse
        ]);
    }

    // 4. Get Crop recommendations
    public function getCropRecommendations(Request $request)
    {
        $lat = $request->query('latitude', 18.5204);
        $lng = $request->query('longitude', 73.8567);
        $soilType = $request->query('soil_type', 'Loamy');
        $region = $request->query('region', 'Central India');
        $temp = $request->query('temp', 30);
        $rainfall = $request->query('rainfall', 800);

        $prompt = "As an agricultural AI expert, recommend the best 3 crops to grow for a farm in {$region} (lat: {$lat}, lng: {$lng}) with {$soilType} soil, average temperature {$temp}°C, and annual rainfall {$rainfall}mm. Format your response strictly as a JSON array where each object has key fields exactly: 'crop_name', 'suitability_percentage' (integer), 'yield' (expected yield range, e.g. '1.5 - 2.2 tons/ha'), 'season' (Kharif, Rabi, Zaid, etc.), 'reason' (short 1-line reason), 'emoji'. Respond with ONLY valid JSON array content without markdown code blocks.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        // Sanitize response to pull valid JSON array
        $jsonStart = strpos($aiResponse, '[');
        $jsonEnd = strrpos($aiResponse, ']');
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($aiResponse, $jsonStart, $jsonEnd - $jsonStart + 1);
            $crops = json_decode($jsonString, true);
        } else {
            $crops = null;
        }

        // Fallback crop array in case AI response doesn't parse
        if (!is_array($crops)) {
            $crops = [
                [
                    'crop_name' => 'Cotton',
                    'suitability_percentage' => 88,
                    'yield' => '1.8 - 2.5 tons/ha',
                    'season' => 'Kharif',
                    'reason' => 'Cotton thrives well in dry warmth and clay/loam soils.',
                    'emoji' => '🌾'
                ],
                [
                    'crop_name' => 'Wheat',
                    'suitability_percentage' => 74,
                    'yield' => '3.0 - 4.5 tons/ha',
                    'season' => 'Rabi',
                    'reason' => 'Wheat fits loamy soil profiles and winter temperatures.',
                    'emoji' => '🌾'
                ]
            ];
        }

        // Save recommendations to database if user is authenticated
        $user = auth('sanctum')->user();
        if ($user) {
            // Clear old recommendations first
            DB::table('crop_recommendations')->where('user_id', $user->id)->delete();
            foreach ($crops as $crop) {
                DB::table('crop_recommendations')->insert([
                    'user_id' => $user->id,
                    'crop_name' => $crop['crop_name'],
                    'reason' => $crop['reason'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $crops
        ]);
    }

    // 5. Get Pesticides/Fertilizers recommendations matched to AgriMarket items
    public function getProductRecommendations(Request $request)
    {
        $crops = $request->query('crops', 'Wheat,Cotton');

        $prompt = "For growing these crops: {$crops} in India, suggest 1 optimal fertilizer product name, 1 pesticide product name, and 1 fungicide product name. Format your output strictly as a JSON array where each object has fields exactly: 'product_name' (specific general product term, e.g. 'NPK 19-19-19', 'Neem Oil Pesticide', 'Copper Oxychloride Fungicide'), 'category' ('fertilizer', 'pesticide', or 'fungicide'), and 'reason'. Respond with ONLY valid JSON array content without markdown code blocks.";

        $aiResponse = $this->geminiService->generateContent($prompt);

        $jsonStart = strpos($aiResponse, '[');
        $jsonEnd = strrpos($aiResponse, ']');
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($aiResponse, $jsonStart, $jsonEnd - $jsonStart + 1);
            $aiProducts = json_decode($jsonString, true);
        } else {
            $aiProducts = null;
        }

        // Fallback products array
        if (!is_array($aiProducts)) {
            $aiProducts = [
                ['product_name' => 'Urea Fertilizer', 'category' => 'fertilizer', 'reason' => 'Boosts nitrogen content for faster leaf canopy growth.'],
                ['product_name' => 'Neem Oil Pesticide', 'category' => 'pesticide', 'reason' => 'Organic defense against aphids and whiteflies.'],
                ['product_name' => 'Fungicide', 'category' => 'fungicide', 'reason' => 'Controls root rot and mildew in high humidity conditions.']
            ];
        }

        // Match items to actual products & shops in database
        $matchedResults = [];
        foreach ($aiProducts as $aiProduct) {
            $nameTerm = strtolower($aiProduct['product_name']);
            $catTerm = strtolower($aiProduct['category']);

            // Find match in products table
            $dbProduct = Product::with('shop')
                ->where(function ($q) use ($nameTerm, $catTerm) {
                    $q->where(DB::raw('lower(name)'), 'like', "%{$nameTerm}%")
                      ->orWhere(DB::raw('lower(description)'), 'like', "%{$nameTerm}%")
                      ->orWhere(DB::raw('lower(name)'), 'like', "%{$catTerm}%");
                })
                ->where('stock', '>', 0)
                ->first();

            // If no direct DB match found, map to defaults in our seeded shops
            if (!$dbProduct) {
                if ($catTerm === 'fertilizer') {
                    // Match to BioGrow Fertilizers (shop_id = 1)
                    $dbProduct = Product::with('shop')->where('shop_id', 1)->first();
                } else if ($catTerm === 'pesticide' || $catTerm === 'fungicide') {
                    // Match to BioGrow/Astra or first product
                    $dbProduct = Product::with('shop')->where('shop_id', 1)->orderBy('id', 'desc')->first();
                }

                if (!$dbProduct) {
                    $dbProduct = Product::with('shop')->first(); // absolute fallback
                }
            }

            if ($dbProduct) {
                $matchedResults[] = [
                    'product_name' => $aiProduct['product_name'],
                    'category' => $aiProduct['category'],
                    'reason' => $aiProduct['reason'],
                    'shop_product_name' => $dbProduct->name,
                    'price' => $dbProduct->price,
                    'stock' => $dbProduct->stock,
                    'image' => $dbProduct->image,
                    'shop_id' => $dbProduct->shop->id,
                    'shop_name' => $dbProduct->shop->name,
                    'shop_position' => [
                        'x' => $dbProduct->shop->position_x,
                        'z' => $dbProduct->shop->position_z
                    ]
                ];

                // Save recommendation to database if user is authenticated
                $user = auth('sanctum')->user();
                if ($user) {
                    DB::table('recommended_products')->insert([
                        'user_id' => $user->id,
                        'product_name' => $dbProduct->name,
                        'shop_id' => $dbProduct->shop->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $matchedResults
        ]);
    }
}
