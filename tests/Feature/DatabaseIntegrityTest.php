<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\University;
use App\Models\Career;
use App\Models\Subject;

class DatabaseIntegrityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba de Integridad Referencial:
     * Verifica que al borrar un usuario, se borre en cascada toda su 
     * información académica (Universidades, Carreras y Materias).
     */
    public function test_user_deletion_cascades_to_academic_entities(): void
    {
        // 1. Arrange (Preparar el escenario)
        $user = User::factory()->create();
        
        $university = University::create([
            'user_id' => $user->id,
            'name' => 'Universidad de Prueba QA',
            'acronym' => 'QA',
        ]);

        $career = Career::create([
            'university_id' => $university->id,
            'name' => 'Carrera de Prueba QA',
            'duration_years' => 3,
        ]);

        $subject = Subject::create([
            'career_id' => $career->id,
            'user_id' => $user->id,
            'name' => 'Materia de Prueba QA',
            'teacher' => null, // Probamos también el campo nullable que parcheamos
            'classroom' => null,
            'color_code' => '#123456',
            'is_active' => true,
        ]);

        // Confirmamos que todo se guardó correctamente
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('universities', ['id' => $university->id]);
        $this->assertDatabaseHas('careers', ['id' => $career->id]);
        $this->assertDatabaseHas('subjects', ['id' => $subject->id]);

        // 2. Act (Ejecutar la acción)
        // Simulamos que el usuario elimina su cuenta
        $user->delete();

        // 3. Assert (Verificar el resultado en cascada)
        // La base de datos debe estar limpia de los registros de este usuario
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('universities', ['id' => $university->id]);
        $this->assertDatabaseMissing('careers', ['id' => $career->id]);
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }
}
