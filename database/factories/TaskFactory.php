<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['normal', 'tp', 'parcial', 'final']);
        
        $grade = null;
        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        $isCompleted = ($status === 'completed');
        
        if ($isCompleted && in_array($type, ['parcial', 'final'])) {
            $grade = fake()->randomFloat(1, 4, 10);
        }

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'task_type' => $type,
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'is_completed' => $isCompleted,
            'status' => $status,
            'due_date' => fake()->dateTimeBetween('-1 month', '+2 months')->format('Y-m-d'),
            'task_time' => fake()->time('H:i'),
            'estimated_time' => fake()->numberBetween(30, 180),
            'reminder' => fake()->boolean(),
            'subject_id' => Subject::factory(),
            'user_id' => User::factory(),
            'team_members' => $type === 'tp' ? fake()->name() . ', ' . fake()->name() : null,
            'submission_format' => $type === 'tp' ? fake()->randomElement(['PDF', 'Presencial', 'Campus']) : null,
            'grade' => $grade,
            'enrollment_date' => $type === 'final' ? fake()->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d') : null,
            'exam_type' => in_array($type, ['parcial', 'final']) ? fake()->randomElement(['Escrito', 'Oral', 'Mixto']) : null,
        ];
    }
}
