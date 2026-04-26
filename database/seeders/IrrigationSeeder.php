<?php

namespace Database\Seeders;

use App\Models\Irrigation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IrrigationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $irrigations = [
            [
                'region_id' => 1, // North India (Punjab, Haryana, Western UP)
                'type' => 'canal',
                'coverage_percentage' => 85.5,
            ],
            [
                'region_id' => 1, // North India - additional irrigation type
                'type' => 'borewell',
                'coverage_percentage' => 12.3,
            ],
            [
                'region_id' => 2, // Central India (Madhya Pradesh, Chhattisgarh)
                'type' => 'rain',
                'coverage_percentage' => 45.2,
            ],
            [
                'region_id' => 2, // Central India - additional irrigation type
                'type' => 'borewell',
                'coverage_percentage' => 38.7,
            ],
            [
                'region_id' => 3, // South India (Tamil Nadu, Karnataka, Telangana)
                'type' => 'canal',
                'coverage_percentage' => 62.8,
            ],
            [
                'region_id' => 3, // South India - additional irrigation type
                'type' => 'borewell',
                'coverage_percentage' => 25.4,
            ],
            [
                'region_id' => 4, // East India (West Bengal, Assam, Odisha)
                'type' => 'rain',
                'coverage_percentage' => 78.9,
            ],
            [
                'region_id' => 4, // East India - additional irrigation type
                'type' => 'canal',
                'coverage_percentage' => 15.6,
            ],
        ];

        foreach ($irrigations as $irrigation) {
            Irrigation::create($irrigation);
        }
    }
}
