<x-app-layout>
    <div class="font-nunito bg-[#F8FAFC] min-h-screen text-[#1E293B]" 
         x-data="{
            searchQuery: localStorage.getItem('kanban_{{ $subject->id }}_search') || '',
            filterType: localStorage.getItem('kanban_{{ $subject->id }}_filterType') || 'all',
            colPendiente: localStorage.getItem('kanban_{{ $subject->id }}_colPendiente') === null ? true : localStorage.getItem('kanban_{{ $subject->id }}_colPendiente') === 'true',
            colProceso: localStorage.getItem('kanban_{{ $subject->id }}_colProceso') === null ? true : localStorage.getItem('kanban_{{ $subject->id }}_colProceso') === 'true',
            colCompletada: localStorage.getItem('kanban_{{ $subject->id }}_colCompletada') === null ? true : localStorage.getItem('kanban_{{ $subject->id }}_colCompletada') === 'true',
            
            init() {
                this.$watch('searchQuery', val => localStorage.setItem('kanban_{{ $subject->id }}_search', val));
                this.$watch('filterType', val => localStorage.setItem('kanban_{{ $subject->id }}_filterType', val));
                this.$watch('colPendiente', val => localStorage.setItem('kanban_{{ $subject->id }}_colPendiente', val));
                this.$watch('colProceso', val => localStorage.setItem('kanban_{{ $subject->id }}_colProceso', val));
                this.$watch('colCompletada', val => localStorage.setItem('kanban_{{ $subject->id }}_colCompletada', val));
            },
            
            toggleCol(col) {
                let openCount = (this.colPendiente ? 1 : 0) + (this.colProceso ? 1 : 0) + (this.colCompletada ? 1 : 0);
                if (this[col]) {
                    if (openCount > 1) this[col] = false;
                } else {
                    this[col] = true;
                }
            },
            checkMatch(title, desc, type) {
                let matchSearch = this.searchQuery === '' || title.toLowerCase().includes(this.searchQuery.toLowerCase()) || (desc && desc.toLowerCase().includes(this.searchQuery.toLowerCase()));
                let matchType = this.filterType === 'all' || type === this.filterType;
                return matchSearch && matchType;
            }
         }">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 lg:pb-12">

            <!-- Header de la materia -->
            <section class="mb-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <a href="{{ route('dashboard') }}" class="w-fit inline-flex items-center justify-center gap-2 text-sm font-bold text-violet-700 bg-violet-50 hover:bg-violet-100 hover:text-violet-800 px-4 py-2 rounded-xl transition-all shadow-sm group mb-4">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Volver al inicio
                    </a>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full" style="background-color: {{ $subject->color_code }}"></span>
                        {{ $subject->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Tablero Kanban de tareas
                    </p>
                </div>

                <a href="{{ route('tasks.create', ['subject_id' => $subject->id, 'allowed_types' => 'tasks']) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-violeta-moderno px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva tarea
                </a>
            </section>

            <!-- TOOLBAR KANBAN -->
            <div class="mb-6 flex flex-col lg:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <!-- Buscador -->
                <div class="relative w-full lg:w-72 shrink-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="searchQuery" placeholder="Buscar por título o descripción..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border-gray-200 rounded-xl text-sm focus:border-violet-500 focus:ring-violet-500">
                </div>

                <!-- Filtros y Agrupamiento -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="flex items-center gap-1 bg-gray-50 p-1 rounded-xl border border-gray-200">
                        <button @click="filterType = 'all'" :class="filterType === 'all' ? 'bg-white shadow text-gray-800' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Todas</button>
                        <button @click="filterType = 'normal'" :class="filterType === 'normal' ? 'bg-white shadow text-gray-800' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Normales</button>
                        <button @click="filterType = 'tp'" :class="filterType === 'tp' ? 'bg-white shadow text-gray-800' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Trabajos Prácticos</button>
                    </div>

                    <!-- Agrupar por (Estilo Moderno) -->
                    <div x-data="{ open: false }" class="relative w-full lg:w-56 shrink-0">
                        <button type="button" @click="open = !open"
                            class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno">
                            
                            <span class="truncate flex-1 text-left">
                                {{ request('group_by') === 'priority' ? 'Agrupar por Prioridad' : 'Vista clásica' }}
                            </span>

                            <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-50 mt-2 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl right-0"
                            style="display:none;">
                            
                            <button type="button" @click="window.location.href = '?group_by=none'" 
                                class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition {{ request('group_by') !== 'priority' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700' }}">
                                Vista clásica
                            </button>

                            <button type="button" @click="window.location.href = '?group_by=priority'" 
                                class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition {{ request('group_by') === 'priority' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700' }}">
                                Agrupar por Prioridad
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $groupBy = request('group_by', 'none');
                $groups = $groupBy === 'priority' ? [
                    'high' => ['color' => 'text-red-700', 'bg' => 'bg-red-50', 'border' => 'border-red-200', 'label' => 'Alta Prioridad'],
                    'medium' => ['color' => 'text-orange-700', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'label' => 'Prioridad Media'],
                    'low' => ['color' => 'text-green-700', 'bg' => 'bg-green-50', 'border' => 'border-green-200', 'label' => 'Baja Prioridad'],
                ] : [
                    'all' => ['color' => 'text-gray-700', 'bg' => 'bg-transparent', 'border' => 'border-transparent', 'label' => '']
                ];
            @endphp

            @foreach($groups as $groupKey => $groupConfig)
                @if($groupBy === 'priority')
                    <div class="mb-4 mt-8 flex items-center gap-3">
                        <h4 class="text-lg font-black uppercase tracking-wider {{ $groupConfig['color'] }}">{{ $groupConfig['label'] }}</h4>
                        <div class="h-px flex-1 {{ $groupConfig['bg'] }} border-t {{ $groupConfig['border'] }}"></div>
                    </div>
                @endif

                <!-- ================= MODO DRAG & DROP REAL ================= -->
                <div class="flex flex-col lg:flex-row gap-5 items-stretch mb-6 swimlane-row">
                    
                    <!-- COLUMNA: PENDIENTES -->
                    <div class="p-4 rounded-2xl flex flex-col border border-gray-200/50 shadow-sm transition-all duration-300 {{ $groupBy === 'none' ? 'bg-gray-100/70' : $groupConfig['bg'] }}"
                         :class="colPendiente ? 'flex-1 min-w-[300px]' : 'w-full lg:w-16 flex-none items-center overflow-hidden cursor-pointer hover:bg-gray-200/50'">
                        
                        <h3 class="font-bold text-gray-700 text-sm mb-4 flex items-center justify-between cursor-pointer w-full" @click="toggleCol('colPendiente')">
                            <div class="flex items-center gap-2" x-show="colPendiente">
                                <span>📋 Pendientes</span>
                                <span class="bg-white/60 text-gray-600 text-xs px-2 py-1 rounded-full count-badge">
                                    {{ $tasks->where('status', 'pending')->when($groupBy === 'priority', fn($q) => $q->where('priority', $groupKey))->count() }}
                                </span>
                            </div>
                            <span x-show="!colPendiente" class="hidden lg:block rotate-90 origin-left whitespace-nowrap translate-y-16 ml-3 text-gray-500 tracking-widest uppercase text-xs">Pendientes</span>
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 transition flex-shrink-0" :class="colPendiente ? '' : 'lg:-rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </h3>
                        
                        <div x-show="colPendiente" x-transition.opacity class="space-y-3 min-h-[150px] flex-1 contenedor-sortable" data-estado="pendiente">
                            @foreach($tasks->where('status', 'pending')->when($groupBy === 'priority', fn($q) => $q->where('priority', $groupKey)) as $task)
                                <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing"
                                     x-show="checkMatch('{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}', '{{ $task->task_type }}')">
                                    <x-task-card id="{{ $task->id }}" status="{{ $task->status }}" title="{{ $task->title }}" subject="{{ $subject->name }}" subjectId="{{ $subject->id }}" type="{{ $task->task_type }}" priority="{{ $task->priority }}" description="{{ $task->description }}" dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}" rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" teamMembers="{{ $task->team_members }}" submissionFormat="{{ $task->submission_format }}" grade="{{ $task->grade }}" enrollmentDate="{{ $task->enrollment_date ? $task->enrollment_date->format('Y-m-d') : '' }}" examType="{{ $task->exam_type }}" :subtasks="$task->nestedSubtasks" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- COLUMNA: EN PROCESO -->
                    <div class="p-4 rounded-2xl flex flex-col border border-gray-200/50 shadow-sm transition-all duration-300 {{ $groupBy === 'none' ? 'bg-gray-100/70' : $groupConfig['bg'] }}"
                         :class="colProceso ? 'flex-1 min-w-[300px]' : 'w-full lg:w-16 flex-none items-center overflow-hidden cursor-pointer hover:bg-gray-200/50'">
                        
                        <h3 class="font-bold text-blue-700 text-sm mb-4 flex items-center justify-between cursor-pointer w-full" @click="toggleCol('colProceso')">
                            <div class="flex items-center gap-2" x-show="colProceso">
                                <span>⚡ En progreso</span>
                                <span class="bg-white/60 text-blue-700 text-xs px-2 py-1 rounded-full count-badge">
                                    {{ $tasks->where('status', 'in_progress')->when($groupBy === 'priority', fn($q) => $q->where('priority', $groupKey))->count() }}
                                </span>
                            </div>
                            <span x-show="!colProceso" class="hidden lg:block rotate-90 origin-left whitespace-nowrap translate-y-16 ml-3 text-blue-400 tracking-widest uppercase text-xs">Progreso</span>
                            <svg class="w-4 h-4 text-blue-400 hover:text-blue-600 transition flex-shrink-0" :class="colProceso ? '' : 'lg:-rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </h3>
                        
                        <div x-show="colProceso" x-transition.opacity class="space-y-3 min-h-[150px] flex-1 contenedor-sortable" data-estado="proceso">
                            @foreach($tasks->where('status', 'in_progress')->when($groupBy === 'priority', fn($q) => $q->where('priority', $groupKey)) as $task)
                                <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing"
                                     x-show="checkMatch('{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}', '{{ $task->task_type }}')">
                                    <x-task-card id="{{ $task->id }}" status="{{ $task->status }}" title="{{ $task->title }}" subject="{{ $subject->name }}" subjectId="{{ $subject->id }}" type="{{ $task->task_type }}" priority="{{ $task->priority }}" description="{{ $task->description }}" dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}" rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" teamMembers="{{ $task->team_members }}" submissionFormat="{{ $task->submission_format }}" grade="{{ $task->grade }}" enrollmentDate="{{ $task->enrollment_date ? $task->enrollment_date->format('Y-m-d') : '' }}" examType="{{ $task->exam_type }}" :subtasks="$task->nestedSubtasks" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- COLUMNA: COMPLETADAS -->
                    <div class="p-4 rounded-2xl flex flex-col border border-gray-200/50 shadow-sm transition-all duration-300 {{ $groupBy === 'none' ? 'bg-gray-100/70' : $groupConfig['bg'] }}"
                         :class="colCompletada ? 'flex-1 min-w-[300px]' : 'w-full lg:w-16 flex-none items-center overflow-hidden cursor-pointer hover:bg-gray-200/50'">
                        
                        <h3 class="font-bold text-green-700 text-sm mb-4 flex items-center justify-between cursor-pointer w-full" @click="toggleCol('colCompletada')">
                            <div class="flex items-center gap-2" x-show="colCompletada">
                                <span>✅ Completadas</span>
                                <span class="bg-white/60 text-green-700 text-xs px-2 py-1 rounded-full count-badge">
                                    {{ $tasks->where('status', 'completed')->when($groupBy === 'priority', fn($q) => $q->where('priority', $groupKey))->count() }}
                                </span>
                            </div>
                            <span x-show="!colCompletada" class="hidden lg:block rotate-90 origin-left whitespace-nowrap translate-y-16 ml-3 text-green-500 tracking-widest uppercase text-xs">Completadas</span>
                            <svg class="w-4 h-4 text-green-500 hover:text-green-700 transition flex-shrink-0" :class="colCompletada ? '' : 'lg:-rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </h3>
                        
                        <div x-show="colCompletada" x-transition.opacity class="space-y-3 min-h-[150px] flex-1 contenedor-sortable" data-estado="completada">
                            @foreach($tasks->where('status', 'completed')->when($groupBy === 'priority', fn($q) => $q->where('priority', $groupKey)) as $task)
                                <div data-id="{{ $task->id }}" class="cursor-grab active:cursor-grabbing"
                                     x-show="checkMatch('{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}', '{{ $task->task_type }}')">
                                    <x-task-card id="{{ $task->id }}" status="{{ $task->status }}" title="{{ $task->title }}" subject="{{ $subject->name }}" subjectId="{{ $subject->id }}" type="{{ $task->task_type }}" priority="{{ $task->priority }}" description="{{ $task->description }}" dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}" rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" teamMembers="{{ $task->team_members }}" submissionFormat="{{ $task->submission_format }}" grade="{{ $task->grade }}" enrollmentDate="{{ $task->enrollment_date ? $task->enrollment_date->format('Y-m-d') : '' }}" examType="{{ $task->exam_type }}" :subtasks="$task->nestedSubtasks" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @endforeach

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
                        if (evt.from === evt.to) return;

                        const innerDiv = evt.item.querySelector('[data-has-subtasks]');
                        if (innerDiv && innerDiv.getAttribute('data-has-subtasks') === 'true') {
                            Swal.fire({
                                title: 'Estado Automático',
                                text: 'El estado de esta tarea se calcula automáticamente porque tiene subtareas. Debes completar las subtareas para avanzar.',
                                icon: 'info',
                                confirmButtonColor: '#8b5cf6',
                                confirmButtonText: 'Entendido',
                                customClass: { popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-violet-500' }
                            });
                            // Revertir el movimiento
                            if (evt.from.children[evt.oldIndex]) {
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                            } else {
                                evt.from.appendChild(evt.item);
                            }
                            return;
                        }

                        const tarjetaId = evt.item.getAttribute('data-id');
                        const estadoDestino = evt.to.getAttribute('data-estado');

                        let backendStatus = 'pending';
                        if (estadoDestino === 'proceso') backendStatus = 'in_progress';
                        if (estadoDestino === 'completada') backendStatus = 'completed';
                        
                        try {
                            await window.apiFetch(`/tasks/${tarjetaId}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ 
                                    status: backendStatus 
                                })
                            });

                            // Notificar al componente Alpine interno para que actualice la píldora visual
                            const cardElement = document.querySelector(`[data-id="${tarjetaId}"] > div`);
                            if (cardElement) {
                                cardElement.dispatchEvent(new CustomEvent('status-updated', { detail: { newStatus: backendStatus } }));
                            }
                            
                            // Disparar evento para actualizar todos los contadores de la vista
                            window.dispatchEvent(new Event('actualizar-contadores'));
                            
                        } catch (error) {
                            console.error('Error de red al conectar con el backend:', error);
                        }
                    }
                });
            });

            // Listener global para recalcular contadores dinámicamente
            window.addEventListener('actualizar-contadores', () => {
                document.querySelectorAll('.contenedor-sortable').forEach(container => {
                    const columnDiv = container.closest('.p-4.rounded-2xl');
                    if (columnDiv) {
                        const badge = columnDiv.querySelector('.count-badge');
                        if (badge) {
                            badge.innerText = container.children.length;
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
