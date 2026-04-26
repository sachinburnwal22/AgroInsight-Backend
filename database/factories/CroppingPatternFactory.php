<?php

namespace Database\Factories;

use App\Models\Crop;
use App\Models\CroppingPattern;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CroppingPattern>
 */
class CroppingPatternFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $region = Region::inRandomOrder()->first() ?? Region::factory()->create();
        $crop = Crop::inRandomOrder()->first() ?? Crop::factory()->create();

        return [
            'region_id' => $region->id,
            'crop_id' => $crop->id,
            'area_percentage' => $this->faker->randomFloat(2, 10, 60),
        ];
    }
}
