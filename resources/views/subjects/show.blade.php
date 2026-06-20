<x-app-layout>
    <div class="font-nunito bg-[#F8FAFC] min-h-screen text-[#1E293B]">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 lg:pb-12">

            <!-- Header de la materia -->
            <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-violeta-moderno flex items-center gap-1 mb-2">
                        ← Volver al inicio
                    </a>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full" style="background-color: {{ $subject->color_code }}"></span>
                        {{ $subject->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Tablero Kanban de tareas
                    </p>
                </div>

                <a href="{{ route('tasks.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-violeta-moderno px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva tarea
                </a>
            </section>

            <!-- ================= MODO DRAG & DROP REAL (Columnas Kanban funcionales) ================= -->
            <div class="flex flex-col lg:flex-row gap-5 items-start">
                
                <!-- COLUMNA: PENDIENTES -->
                <div class="bg-gray-100/70 p-4 rounded-2xl w-full lg:w-1/3 flex flex-col border border-gray-200/50 shadow-sm">
                    <h3 class="font-bold text-gray-700 text-sm mb-4 flex items-center justify-between">
                        <span>📋 Pendientes</span>
                        <span id="count-pendiente" class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $tasks->where('status', 'pending')->count() }}</span>
                    </h3>
                    <div id="col-pendiente" class="space-y-3 min-h-[450px] contenedor-sortable" data-estado="pendiente">
                        @foreach($tasks->where('status', 'pending') as $task)
                            <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing">
                                <x-task-card 
                                    id="{{ $task->id }}"
                                    status="{{ $task->status }}"
                                    title="{{ $task->title }}"
                                    subject="{{ $subject->name }}"
                                    type="{{ $task->task_type }}"
                                    priority="{{ $task->priority }}"
                                    description="{{ $task->description }}"
                                    dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}"
                                    rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- COLUMNA: EN PROCESO -->
                <div class="bg-gray-100/70 p-4 rounded-2xl w-full lg:w-1/3 flex flex-col border border-gray-200/50 shadow-sm">
                    <h3 class="font-bold text-blue-700 text-sm mb-4 flex items-center justify-between">
                        <span>⚡ En progreso</span>
                        <span id="count-proceso" class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">{{ $tasks->where('status', 'in_progress')->count() }}</span>
                    </h3>
                    <div id="col-proceso" class="space-y-3 min-h-[450px] contenedor-sortable" data-estado="proceso">
                        @foreach($tasks->where('status', 'in_progress') as $task)
                            <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing">
                                <x-task-card 
                                    id="{{ $task->id }}"
                                    status="{{ $task->status }}"
                                    title="{{ $task->title }}"
                                    subject="{{ $subject->name }}"
                                    type="{{ $task->task_type }}"
                                    priority="{{ $task->priority }}"
                                    description="{{ $task->description }}"
                                    dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}"
                                    rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- COLUMNA: COMPLETADAS -->
                <div class="bg-gray-100/70 p-4 rounded-2xl w-full lg:w-1/3 flex flex-col border border-gray-200/50 shadow-sm">
                    <h3 class="font-bold text-green-700 text-sm mb-4 flex items-center justify-between">
                        <span>✅ Completadas</span>
                        <span id="count-completada" class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">{{ $tasks->where('status', 'completed')->count() }}</span>
                    </h3>
                    <div id="col-completada" class="space-y-3 min-h-[450px] contenedor-sortable" data-estado="completada">
                        @foreach($tasks->where('status', 'completed') as $task)
                            <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing">
                                <x-task-card 
                                    id="{{ $task->id }}"
                                    status="{{ $task->status }}"
                                    title="{{ $task->title }}"
                                    subject="{{ $subject->name }}"
                                    type="{{ $task->task_type }}"
                                    priority="{{ $task->priority }}"
                                    description="{{ $task->description }}"
                                    dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}"
                                    rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Script de Inicialización utilizando la instancia global -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const columnas = document.querySelectorAll('.contenedor-sortable');

            columnas.forEach(columna => {
                new window.Sortable(columna, {
                    group: 'kanban-tareas',
                    animation: 150,
                    ghostClass: 'bg-purple-50',
                    chosenClass: 'opacity-50',
                    
                    onEnd: async function (evt) {
                        const tarjetaId = evt.item.getAttribute('data-id');
                        const estadoDestino = evt.to.getAttribute('data-estado');

                        let backendStatus = 'pending';
                        if (estadoDestino === 'proceso') backendStatus = 'in_progress';
                        if (estadoDestino === 'completada') backendStatus = 'completed';
                        
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            
                            await fetch(`/tasks/${tarjetaId}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ 
                                    status: backendStatus 
                                })
                            });
                            
                            // Actualizar contadores dinámicamente contando las tarjetas (hijos) en cada columna
                            document.getElementById('count-pendiente').innerText = document.getElementById('col-pendiente').children.length;
                            document.getElementById('count-proceso').innerText = document.getElementById('col-proceso').children.length;
                            document.getElementById('count-completada').innerText = document.getElementById('col-completada').children.length;
                            
                        } catch (error) {
                            console.error('Error de red al conectar con el backend:', error);
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
