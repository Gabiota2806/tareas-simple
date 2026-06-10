<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el usuario dueño de estos datos de prueba
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        // 2. Crear Universidad 1
        $utn = \App\Models\University::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Universidad Tecnológica Nacional - Facultad Regional Resistencia',
            'acronym' => 'UTN FRRE',
        ]);

        // 3. Crear Carreras de la Universidad 1
        $carrerasUtn = [
            ['name' => 'Ingeniería en Sistemas de Información', 'duration_years' => 5],
            ['name' => 'Tecnicatura Universitaria en Programación', 'duration_years' => 2],
        ];

        foreach ($carrerasUtn as $c) {
            \App\Models\Career::firstOrCreate([
                'university_id' => $utn->id,
                'name' => $c['name'],
            ], ['duration_years' => $c['duration_years']]);
        }

        // OPCIONAL: Si quisieras usar los factories para generar 5 universidades extra:
        // \App\Models\University::factory(5)->for($user)->has(\App\Models\Career::factory()->count(2))->create();
    }
}
