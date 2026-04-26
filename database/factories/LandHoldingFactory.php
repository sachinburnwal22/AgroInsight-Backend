<?php

namespace Database\Factories;

use App\Models\LandHolding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LandHolding>
 */
class LandHoldingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'avg_land_size' => $this->faker->randomFloat(2, 0.5, 5.0),
            'small_farmers_pct' => $this->faker->randomFloat(2, 50, 90),
        ];
    }
}
