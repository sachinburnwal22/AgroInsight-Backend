<?php

namespace Database\Factories;

use App\Models\Crop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Crop>
 */
class CropFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cropOptions = [
            ['name' => 'Rice', 'season' => 'kharif', 'water_requirement' => $this->faker->randomFloat(2, 1200, 2200)],
            ['name' => 'Wheat', 'season' => 'rabi', 'water_requirement' => $this->faker->randomFloat(2, 2, 4)],
            ['name' => 'Maize', 'season' => 'kharif', 'water_requirement' => $this->faker->randomFloat(2, 500, 800)],
            ['name' => 'Cotton', 'season' => 'kharif', 'water_requirement' => $this->faker->randomFloat(2, 600, 900)],
            ['name' => 'Sugarcane', 'season' => 'kharif', 'water_requirement' => $this->faker->randomFloat(2, 1500, 2500)],
        ];

        $crop = $this->faker->randomElement($cropOptions);

        return [
            'name' => $crop['name'],
            'water_requirement' => $crop['water_requirement'],
            'season' => $crop['season'],
        ];
    }
}
