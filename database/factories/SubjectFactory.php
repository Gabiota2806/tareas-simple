<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Career;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Matemáticas', 'Física', 'Química', 'Programación', 'Bases de Datos', 'Historia', 'Inglés', 'Metodología', 'Arquitectura de Computadoras', 'Sistemas Operativos']),
            'teacher' => fake()->name(),
            'classroom' => 'Aula ' . fake()->numberBetween(1, 20),
            'color_code' => fake()->randomElement(['#8B5CF6', '#EC4899', '#EF4444', '#F97316', '#F59E0B', '#10B981', '#06B6D4', '#3B82F6']),
            'is_active' => true,
            'career_id' => Career::factory(),
            'user_id' => User::factory(),
            'track_attendance' => false,
            'max_absences' => 0,
            'absences_count' => 0,
            'approval_type' => fake()->randomElement(['promocional', 'regular', 'libre', '']),
            'final_grade' => null,
        ];
    }
}
