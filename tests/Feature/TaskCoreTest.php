<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Subject;
use App\Models\Task;
use App\Models\University;
use App\Models\Career;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCoreTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Subject $activeSubject;
    private Subject $inactiveSubject;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Crear Usuario
        $this->user = User::factory()->create();

        // 2. Crear jerarquía académica para que no falle la FK de Subject
        $university = University::create([
            'name' => 'UTN FRRE',
            'user_id' => $this->user->id
        ]);

        $career = Career::create([
            'name' => 'Ingeniería en Sistemas',
            'university_id' => $university->id,
            'user_id' => $this->user->id
        ]);

        // 3. Crear Materia Activa
        $this->activeSubject = Subject::create([
            'name' => 'Metodología',
            'color_code' => '#000000',
            'is_active' => true,
            'career_id' => $career->id,
            'user_id' => $this->user->id
        ]);

        // 4. Crear Materia Inactiva (Aprobada)
        $this->inactiveSubject = Subject::create([
            'name' => 'Álgebra',
            'color_code' => '#FF0000',
            'is_active' => false,
            'career_id' => $career->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_can_create_task_on_active_subject(): void
    {
        $response = $this->actingAs($this->user)->postJson('/tasks', [
            'title' => 'Trabajo Final',
            'task_type' => 'tp',
            'priority' => 'high',
            'subject_id' => $this->activeSubject->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Trabajo Final',
            'is_completed' => false
        ]);
    }

    public function test_cannot_create_task_on_inactive_subject_returns_422(): void
    {
        // Requisito estricto de la tarea 4.1.6
        $response = $this->actingAs($this->user)->postJson('/tasks', [
            'title' => 'Estudiar para final',
            'task_type' => 'final',
            'priority' => 'high',
            'subject_id' => $this->inactiveSubject->id
        ]);

        // Debe rebotar con Unprocessable Entity
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Materia no válida o inactiva.'
        ]);
    }

    public function test_can_update_task_status_for_kanban(): void
    {
        // Crear tarea manualmente
        $task = Task::create([
            'title' => 'Tarea a mover',
            'task_type' => 'normal',
            'priority' => 'low',
            'subject_id' => $this->activeSubject->id,
            'user_id' => $this->user->id,
            'is_completed' => false,
            'is_deleted' => false
        ]);

        // Simular arrastrar la tarea a la columna "Completadas" en el Kanban
        $response = $this->actingAs($this->user)->patchJson("/tasks/{$task->id}", [
            'is_completed' => true
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_completed' => true
        ]);
    }

    public function test_can_soft_delete_task(): void
    {
        $task = Task::create([
            'title' => 'Tarea a borrar',
            'task_type' => 'normal',
            'priority' => 'medium',
            'subject_id' => $this->activeSubject->id,
            'user_id' => $this->user->id,
            'is_completed' => false,
            'is_deleted' => false
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/tasks/{$task->id}");

        $response->assertStatus(200);
        
        // Verifica que no se borró físicamente, sino lógicamente
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_deleted' => true
        ]);
    }
}
