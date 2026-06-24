<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\University;
use App\Models\Career;
use App\Models\Subject;
use App\Models\Task;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear usuario principal de prueba si no existe
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Crear 5 usuarios ficticios más
        $users = User::factory(5)->create();
        $users->push($testUser);

        // 3. Generar jerarquía para cada usuario
        foreach ($users as $user) {
            
            // Cada usuario tiene 1 a 2 universidades
            $universities = University::factory(rand(1, 2))->create([
                'user_id' => $user->id,
            ]);

            foreach ($universities as $university) {
                // Cada universidad tiene 1 a 3 carreras
                $careers = Career::factory(rand(1, 3))->create([
                    'university_id' => $university->id,
                ]);

                foreach ($careers as $career) {
                    // Cada carrera tiene 3 a 5 materias
                    $subjects = Subject::factory(rand(3, 5))->create([
                        'user_id' => $user->id,
                        'career_id' => $career->id,
                    ]);

                    foreach ($subjects as $subject) {
                        // Cada materia tiene 5 a 10 tareas/exámenes
                        Task::factory(rand(5, 10))->create([
                            'user_id' => $user->id,
                            'subject_id' => $subject->id,
                        ]);
                    }
                }
            }
        }
    }
}
