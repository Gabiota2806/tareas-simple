<?php
    use Illuminate\Database\Migrations\Migration;                                                                                                                     
    use Illuminate\Database\Schema\Blueprint;                                                                                                                         
    use Illuminate\Support\Facades\Schema;                                                                                                                            
                                                                                                                                                                      
    return new class extends Migration                                                                                                                                
    {                                                                                                                                                                 
        public function up(): void                                                                                                                                    
        {                                                                                                                                                             
            Schema::create('universities', function (Blueprint $table) {                                                                                              
                // Identificador único de cada universidad
                $table->id();

                // Llave foránea hacia el usuario dueño de esta universidad.
                // Si el usuario se elimina, todas sus universidades se eliminan en cascada.
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();

                // Campos principales de la entidad "universidad"
                $table->string('name', 150); // ej. "Universidad Tecnológica Nacional"
                $table->string('acronym', 20)->nullable(); // ej. "UTN" (opcional)

                // Control de creación y actualización de registros
                $table->timestamps();
            });

        }

        public function down(): void
        {
            Schema::dropIfExists('universities');
        }
    };