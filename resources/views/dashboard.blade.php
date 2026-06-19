<x-app-layout>
    <!-- Contenedor general con tipografía Nunito y fondo gris claro del mockup -->
    <div class="font-nunito bg-[#F8FAFC] min-h-screen flex text-[#1E293B]">
        
        <!-- ================= CONTENIDO PRINCIPAL DE LA APLICACIÓN ================= -->
        <!-- Inicializamos Alpine.js con dos estados: la pestaña activa ('todas') y el modo de vista ('mockup') -->
        <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 lg:pb-12">
            
            <!-- Barra Superior: Buscador y Botón de Tarea -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                

                <a href="{{ route('tasks.create') }}" class="bg-violeta-moderno hover:bg-opacity-90 text-white px-5 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition-all self-end md:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Nueva tarea
                </a>
            </div>

            <!-- Filtros de Pestañas (Solo visibles y funcionales en la Vista Mockup plana) -->
            <div x-show="vista === 'mockup'" x-transition class="flex items-center gap-2 overflow-x-auto pb-3 mb-8 border-b border-gray-100/50 scrollbar-none">
                <button @click="pestaña = 'todas'" :class="pestaña === 'todas' ? 'bg-violeta-moderno text-white' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-1.5 rounded-xl text-xs font-bold shadow-sm transition-all">Todas</button>
                <button @click="pestaña = 'pendiente'" :class="pestaña === 'pendiente' ? 'bg-violeta-moderno text-white' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-1.5 rounded-xl text-xs font-bold border border-gray-100 transition-all">Pendientes</button>
                <button @click="pestaña = 'proceso'" :class="pestaña === 'proceso' ? 'bg-violeta-moderno text-white' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-1.5 rounded-xl text-xs font-bold border border-gray-100 transition-all">En progreso</button>
                <button @click="pestaña = 'completada'" :class="pestaña === 'completada' ? 'bg-violeta-moderno text-white' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-1.5 rounded-xl text-xs font-bold border border-gray-100 transition-all">Completadas</button>
            </div>

            <!-- ================= MODO DRAG & DROP REAL (Columnas Kanban funcionales) ================= -->
            <div class="flex flex-col lg:flex-row gap-5 items-start">
                
                <!-- COLUMNA: PENDIENTES -->
                <div class="bg-gray-100/70 p-4 rounded-2xl w-full lg:w-1/3 flex flex-col border border-gray-200/50">
                    <h3 class="font-bold text-gray-700 text-sm mb-4 flex items-center justify-between">
                        <span>📋 Pendientes</span>
                    </h3>
                    <div id="col-pendiente" class="space-y-3 min-h-[450px] contenedor-sortable" data-estado="pendiente">
                        @foreach($tasks->where('is_completed', false) as $task)
                            <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing">
                                <x-task-card 
                                    title="{{ $task->title }}"
                                    subject="{{ $task->subject->name ?? 'Sin materia' }}"
                                    type="{{ $task->task_type }}"
                                    priority="{{ $task->priority }}"
                                    description="{{ $task->description }}"
                                    dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- COLUMNA: EN PROCESO (Temporalmente vacía al cargar) -->
                <div class="bg-gray-100/70 p-4 rounded-2xl w-full lg:w-1/3 flex flex-col border border-gray-200/50">
                    <h3 class="font-bold text-blue-700 text-sm mb-4 flex items-center justify-between">
                        <span>⚡ En progreso</span>
                    </h3>
                    <div id="col-proceso" class="space-y-3 min-h-[450px] contenedor-sortable" data-estado="proceso">
                        <!-- Destino temporal -->
                    </div>
                </div>

                <!-- COLUMNA: COMPLETADAS -->
                <div class="bg-gray-100/70 p-4 rounded-2xl w-full lg:w-1/3 flex flex-col border border-gray-200/50">
                    <h3 class="font-bold text-green-700 text-sm mb-4 flex items-center justify-between">
                        <span>✅ Completadas</span>
                    </h3>
                    <div id="col-completada" class="space-y-3 min-h-[450px] contenedor-sortable" data-estado="completada">
                        @foreach($tasks->where('is_completed', true) as $task)
                            <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing">
                                <x-task-card 
                                    title="{{ $task->title }}"
                                    subject="{{ $task->subject->name ?? 'Sin materia' }}"
                                    type="{{ $task->task_type }}"
                                    priority="{{ $task->priority }}"
                                    description="{{ $task->description }}"
                                    dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div> <!-- <-- CIERRA EL CONTENEDOR FLEX/GRID DE LAS COLUMNAS -->
        </main>


        <!-- Botón Flotante (Exclusivo Vista Móvil) -->
        <div class="fixed bottom-6 right-6 lg:hidden z-50">
            <a href="{{ route('tasks.create') }}" class="bg-violeta-moderno text-white w-14 h-14 rounded-xl flex items-center justify-center text-2xl shadow-lg font-bold active:scale-95 transition-transform hover:bg-opacity-90">
                +
            </a>
        </div>

    </div>

        <!-- Script de Inicialización utilizando la instancia global -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Buscamos todas las columnas grises que tengan la clase de arrastre
            const columnas = document.querySelectorAll('.contenedor-sortable');

            columnas.forEach(columna => {
                // Usamos window.Sortable que registramos en app.js
                new window.Sortable(columna, {
                    group: 'kanban-tareas', // LLAVE MAESTRA: Permite mover tarjetas ENTRE columnas
                    animation: 150,         // Movimiento fluido en milisegundos
                    ghostClass: 'bg-purple-50', // Fondo de la columna mientras arrastras
                    chosenClass: 'opacity-50',   // Transparencia al seleccionar la tarjeta
                    
                    // Evento automático al soltar la tarjeta
                    onEnd: async function (evt) {
                        const tarjetaId = evt.item.getAttribute('data-id');
                        const estadoDestino = evt.to.getAttribute('data-estado');

                        console.log(`¡Éxito! Tarjeta ${tarjetaId} movida a: ${estadoDestino}`);

                        // Aquí se enviará la petición PATCH en la siguiente actividad
                                                // Conectamos directamente al endpoint PATCH oficial de UniTask
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            
                            // El backend espera un booleano 'is_completed'
                            const isCompleted = (estadoDestino === 'completada');
                            
                            // La URL oficial usa /tasks/ seguido del ID de la tarea
                            await fetch(`/tasks/${tarjetaId}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ 
                                    is_completed: isCompleted 
                                })
                            });
                            
                            console.log(`¡Petición enviada! Tarea ${tarjetaId} actualizada a: ${estadoDestino}`);
                        } catch (error) {
                            console.error('Error de red al conectar con el backend:', error);
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>

