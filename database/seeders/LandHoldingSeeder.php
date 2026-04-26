<?php

namespace Database\Seeders;

use App\Models\LandHolding;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandHoldingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $landHoldings = [
            [
                'region_id' => 1, // North India (Punjab, Haryana, Western UP)
                'avg_land_size' => 4.2,
                'small_farmers_pct' => 67.5,
            ],
            [
                'region_id' => 2, // Central India (Madhya Pradesh, Chhattisgarh)
                'avg_land_size' => 3.8,
                'small_farmers_pct' => 72.3,
            ],
            [
                'region_id' => 3, // South India (Tamil Nadu, Karnataka, Telangana)
                'avg_land_size' => 2.9,
                'small_farmers_pct' => 78.1,
            ],
            [
                'region_id' => 4, // East India (West Bengal, Assam, Odisha)
                'avg_land_size' => 1.8,
                'small_farmers_pct' => 85.2,
            ],
        ];

        foreach ($landHoldings as $landHolding) {
            LandHolding::create($landHolding);
        }
    }
}