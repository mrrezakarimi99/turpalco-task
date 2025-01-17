<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'price'      => $this->faker->randomFloat(2, 10, 100),
            'start_time' => now()->toDateTimeString(),
            'end_time'   => now()->addWeek()->toDateTimeString(),
        ];
    }
}
