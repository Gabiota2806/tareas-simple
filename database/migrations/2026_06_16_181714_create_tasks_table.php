<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 200); // Título de la tarea
            
            $table->text('description')->nullable(); // Descripción detallada de la tarea (opcional)
            $table->enum('task_type', ['parcial', 'final', 'tp', 'normal']); // Tipo de tarea
            $table->enum('priority', ['low', 'medium', 'high']); // Prioridad de la tarea
            $table->boolean('is_completed')->default(false); // Estado de la tarea (completada o no)
            $table->date('due_date')->nullable();

            // Relación con la tabla subjects
            $table->foreignId('subject_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relación con la tabla users
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

                // Agrega la columna parent_id para la relación de tareas anidadas 
           $table->foreignId('parent_id')
                ->nullable()
                ->constrained('tasks')
                ->cascadeOnDelete();

            $table->boolean('is_deleted')->default(false);   
     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
