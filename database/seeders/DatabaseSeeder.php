<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\CroppingPattern;
use App\Models\Irrigation;
use App\Models\LandHolding;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $cropAttributes = [
            ['name' => 'Rice', 'season' => 'kharif', 'water_requirement' => 1800],
            ['name' => 'Wheat', 'season' => 'rabi', 'water_requirement' => 1200],
            ['name' => 'Maize', 'season' => 'kharif', 'water_requirement' => 650],
            ['name' => 'Cotton', 'season' => 'kharif', 'water_requirement' => 850],
            ['name' => 'Sugarcane', 'season' => 'kharif', 'water_requirement' => 2000],
        ];

        $crops = collect($cropAttributes)->map(fn (array $attributes) => Crop::factory()->create($attributes));
        $cropMap = $crops->keyBy('name');

        $regionProfiles = [
            [
                'name' => 'Punjab Plains',
                'state' => 'Punjab',
                'soil_type' => 'Alluvial',
                'climate' => 'Humid Subtropical',
                'irrigation_count' => 3,
                'crop_weights' => [
                    'Wheat' => 55,
                    'Rice' => 30,
                    'Cotton' => 15,
                ],
            ],
            [
                'name' => 'Bihar Floodplains',
                'state' => 'Bihar',
                'soil_type' => 'Alluvial',
                'climate' => 'Humid Subtropical',
                'irrigation_count' => 2,
                'crop_weights' => [
                    'Rice' => 60,
                    'Maize' => 25,
                    'Sugarcane' => 15,
                ],
            ],
            [
                'name' => 'Rajasthan Dry Zone',
                'state' => 'Rajasthan',
                'soil_type' => 'Sandy',
                'climate' => 'Semi-arid',
                'irrigation_count' => 1,
                'crop_weights' => [
                    'Cotton' => 45,
                    'Maize' => 30,
                    'Wheat' => 25,
                ],
            ],
        ];

        foreach ($regionProfiles as $profile) {
            $region = Region::factory()->create([
                'name' => $profile['name'],
                'state' => $profile['state'],
                'soil_type' => $profile['soil_type'],
                'climate' => $profile['climate'],
            ]);

            LandHolding::factory()->create([
                'region_id' => $region->id,
            ]);

            Irrigation::factory()
                ->count($profile['irrigation_count'])
                ->create([
                    'region_id' => $region->id,
                ]);

            foreach ($profile['crop_weights'] as $cropName => $weight) {
                $crop = $cropMap[$cropName];
                CroppingPattern::factory()->create([
                    'region_id' => $region->id,
                    'crop_id' => $crop->id,
                    'area_percentage' => $weight,
                ]);
            }
        }

        Region::factory()
            ->count(7)
            ->create()
            ->each(function (Region $region) use ($crops) {
                LandHolding::factory()->create([
                    'region_id' => $region->id,
                ]);

                Irrigation::factory()
                    ->count(rand(2, 3))
                    ->create([
                        'region_id' => $region->id,
                    ]);

                $selectedCrops = $crops->shuffle()->take(rand(2, 4));
                foreach ($selectedCrops as $crop) {
                    CroppingPattern::factory()->create([
                        'region_id' => $region->id,
                        'crop_id' => $crop->id,
                    ]);
                }
            });
    }
}
