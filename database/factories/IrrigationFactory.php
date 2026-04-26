<?php

namespace Database\Factories;

use App\Models\Irrigation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Irrigation>
 */
class IrrigationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'canal',
            'rain',
            'borewell',
        ];

        $type = $this->faker->randomElement($types);

        $coverageRanges = [
            'canal' => [40, 90],
            'rain' => [20, 70],
            'borewell' => [30, 80],
        ];

        [$min, $max] = $coverageRanges[$type];

        return [
            'type' => $type,
            'coverage_percentage' => $this->faker->randomFloat(2, $min, $max),
        ];
    }
}
