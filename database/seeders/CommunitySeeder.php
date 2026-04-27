<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $communities = [
            ['name' => 'North India Farmers', 'region' => 'North India', 'description' => 'A community for farmers in North India. Discuss wheat, mustard, and climate strategies.'],
            ['name' => 'South India Planters', 'region' => 'South India', 'description' => 'Connect with paddy, coffee, and spice growers from the southern states.'],
            ['name' => 'East India Cultivators', 'region' => 'East India', 'description' => 'Share knowledge on rice, jute, and tea cultivation in the eastern regions.'],
            ['name' => 'West India Growers', 'region' => 'West India', 'description' => 'A hub for cotton, groundnut, and sugarcane farmers from the west.'],
            ['name' => 'Central India Agriculturists', 'region' => 'Central India', 'description' => 'Discussions for soybean, pulses, and wheat farmers in the central belt.'],
        ];

        foreach ($communities as $community) {
            Community::firstOrCreate(['name' => $community['name']], $community);
        }
    }
}

