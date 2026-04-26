<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'name' => 'Punjab Region',
                'state' => 'Punjab',
                'soil_type' => 'Alluvial & Loamy',
                'climate' => 'Subtropical',
            ],
            [
                'name' => 'Bihar Agro Zone',
                'state' => 'Bihar',
                'soil_type' => 'Alluvial',
                'climate' => 'Humid Subtropical',
            ],
            [
                'name' => 'Maharashtra Plateau',
                'state' => 'Maharashtra',
                'soil_type' => 'Black & Red',
                'climate' => 'Tropical Wet and Dry',
            ],
            [
                'name' => 'Tamil Nadu Plains',
                'state' => 'Tamil Nadu',
                'soil_type' => 'Red & Laterite',
                'climate' => 'Tropical Monsoon',
            ],
            [
                'name' => 'Rajasthan Arid Zone',
                'state' => 'Rajasthan',
                'soil_type' => 'Sandy & Loamy',
                'climate' => 'Arid to Semi-arid',
            ],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
