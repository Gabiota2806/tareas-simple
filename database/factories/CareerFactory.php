<?php

namespace Database\Factories;

use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Career>
 */
class CareerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'university_id' => \App\Models\University::factory(),
            'name' => 'Licenciatura en ' . $this->faker->jobTitle(),
            'duration_years' => $this->faker->numberBetween(2, 6),
        ];
    }
}
