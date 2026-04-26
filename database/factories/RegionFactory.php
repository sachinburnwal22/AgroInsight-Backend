<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Region>
 */
class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regionOptions = [
            ['name' => 'Amritsar District', 'state' => 'Punjab', 'soil_type' => 'Alluvial', 'climate' => 'Humid'],
            ['name' => 'Patna Plains', 'state' => 'Bihar', 'soil_type' => 'Alluvial', 'climate' => 'Humid'],
            ['name' => 'Nagpur Plateau', 'state' => 'Maharashtra', 'soil_type' => 'Black', 'climate' => 'Tropical'],
            ['name' => 'Coimbatore Region', 'state' => 'Tamil Nadu', 'soil_type' => 'Red', 'climate' => 'Tropical'],
            ['name' => 'Jaipur District', 'state' => 'Rajasthan', 'soil_type' => 'Sandy', 'climate' => 'Semi-arid'],
            ['name' => 'Kolkata Belt', 'state' => 'West Bengal', 'soil_type' => 'Alluvial', 'climate' => 'Humid'],
            ['name' => 'Hubballi Region', 'state' => 'Karnataka', 'soil_type' => 'Red', 'climate' => 'Tropical'],
            ['name' => 'Ahmedabad Zone', 'state' => 'Gujarat', 'soil_type' => 'Black', 'climate' => 'Semi-arid'],
            ['name' => 'Lucknow Plains', 'state' => 'Uttar Pradesh', 'soil_type' => 'Alluvial', 'climate' => 'Humid'],
            ['name' => 'Hyderabad Basin', 'state' => 'Telangana', 'soil_type' => 'Red', 'climate' => 'Tropical'],
        ];

        $region = $this->faker->randomElement($regionOptions);

        return [
            'name' => $region['name'],
            'state' => $region['state'],
            'soil_type' => $region['soil_type'],
            'climate' => $region['climate'],
        ];
    }
}
