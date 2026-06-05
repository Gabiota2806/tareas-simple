<?php
    use Illuminate\Database\Migrations\Migration;                                                                                                                     
    use Illuminate\Database\Schema\Blueprint;                                                                                                                         
    use Illuminate\Support\Facades\Schema;                                                                                                                            
                                                                                                                                                                      
    return new class extends Migration                                                                                                                                
    {                                                                                                                                                                 
        public function up(): void                                                                                                                                    
        {                                                                                                                                                             
            Schema::create('universities', function (Blueprint $table) {                                                                                              
                $table->id();                                                                                                                                         
                // Llave foránea hacia el usuario dueño de esta universidad                                                                                           
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();                                                                                       
                                                                                                                                                                      
                // Campos de datos                                                                                                                                    
                $table->string('name', 150); // ej. "Universidad Tecnológica Nacional"                                                                                
                $table->string('acronym', 20)->nullable(); // ej. "UTN" (Opcional)                                                                                    
                                                                                                                                                                      
                $table->timestamps();                                                                                                                                 
            });                                                                                                                                                       
        }                                                                                                                                                             
                                                                                                                                                                      
        public function down(): void                                                                                                                                  
        {                                                                                                                                                             
            Schema::dropIfExists('universities');                                                                                                                     
        }                                                                                                                                                             
    };