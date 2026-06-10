<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<University>
 */
class UniversityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // Crea un usuario si no se le pasa uno
            'name' => $this->faker->unique()->company() . ' University',
            'acronym' => strtoupper($this->faker->lexify('???')),
        ];
    }
}
