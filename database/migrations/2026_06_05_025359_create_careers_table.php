<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            // Identificador único de cada carrera
            $table->id();

            // Llave foránea hacia la universidad a la cual pertenece la carrera.
            // Si la universidad es eliminada, esta carrera también se elimina en cascada.
            $table->foreignId('university_id')->constrained()->cascadeOnDelete();

            // Campos principales de la entidad "carrera"
            $table->string('name', 150); // ej. "Ingeniería en Sistemas"
            $table->integer('duration_years')->nullable(); // ej. 5 (opcional, utilidad futura)

            // Control de creación y actualización de registros
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
