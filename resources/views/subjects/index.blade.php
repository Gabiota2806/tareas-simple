<x-app-layout>

    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 font-nunito flex-shrink-0">
                    Mis Materias
                </h1>
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    <form method="GET" action="{{ route('subjects.index') }}" class="w-full sm:w-64">
                        <input type="hidden" name="is_active" value="{{ $isActive ? 1 : 0 }}">
                        <div x-data="{ open: false }" class="relative w-full">
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300">
                                
                                <span class="truncate flex-1 text-left">
                                    @if($selectedCareer)
                                        {{ $careers->firstWhere('id', $selectedCareer)->name ?? 'Todas las carreras' }}
                                    @else
                                        Todas las carreras
                                    @endif
                                </span>

                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-2 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl"
                                style="display:none;">
                                
                                <button type="submit" name="career_id" value="" 
                                    class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition {{ !$selectedCareer ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700' }}">
                                    Todas las carreras
                                </button>

                                @foreach($careers as $career)
                                    <button type="submit" name="career_id" value="{{ $career->id }}" 
                                        class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition {{ $selectedCareer == $career->id ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700' }}">
                                        {{ $career->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('subjects.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all font-nunito flex-shrink-0">
                        + Nueva Materia
                    </a>
                </div>
            </div>

            <!-- Pestañas de estado -->
            <div class="flex border-b border-gray-200 mb-6 font-nunito">
                <a href="{{ route('subjects.index', ['is_active' => 1, 'career_id' => request('career_id')]) }}" 
                   class="px-6 py-3 font-bold text-sm transition {{ $isActive ? 'border-b-2 border-violet-600 text-violet-600' : 'text-gray-500 hover:text-violet-600 hover:bg-gray-50 rounded-t-lg' }}">
                    Materias Activas
                </a>
                <a href="{{ route('subjects.index', ['is_active' => 0, 'career_id' => request('career_id')]) }}" 
                   class="px-6 py-3 font-bold text-sm transition {{ !$isActive ? 'border-b-2 border-violet-600 text-violet-600' : 'text-gray-500 hover:text-violet-600 hover:bg-gray-50 rounded-t-lg' }}">
                    Materias Archivadas
                </a>
            </div>



            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @forelse($subjects as $subject)
                    <div x-data="{ openModal: false, active: {{ $subject->is_active ? 'true' : 'false' }}, tabIsActive: {{ $isActive ? 'true' : 'false' }} }"
                        x-show="active === tabIsActive"
                        x-transition.opacity.duration.300ms
                        :class="active ? 'bg-white border-violet-500' : 'bg-gray-100 border-gray-400 opacity-75'"
                        class="rounded-2xl shadow-md border-t-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative"
                        style="border-top-color: {{ $subject->color_code ?? '#8B5CF6' }}">
                        
                        <div class="p-5 cursor-pointer" @click="if(!$event.target.closest('button') && !$event.target.closest('a') && !$event.target.closest('input') && !$event.target.closest('label')) openModal = true">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">
                                {{ $subject->name }}
                            </h2>

                            <div class="space-y-1 text-gray-600">
                                <p>📍 Aula {{ $subject->classroom ?? 'No asignada' }}</p>
                                <p>👨‍🏫 Docente: {{ $subject->teacher ?? 'Sin asignar' }}</p>
                                
                                <!-- Bloque de Detalles Académicos -->
                                <div class="mt-3 bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-gray-700">📋 Tipo:</span>
                                        @if($subject->approval_type)
                                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold uppercase">{{ $subject->approval_type }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Sin definir</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-gray-700">🎯 Nota Final:</span>
                                        @if($subject->final_grade)
                                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-bold">{{ $subject->final_grade }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs italic">-</span>
                                        @endif
                                    </div>
                                    
                                    @php
                                        $partials = $subject->tasks ? $subject->tasks->where('task_type', 'parcial')->whereNotNull('grade') : collect();
                                    @endphp
                                    @if($partials->count() > 0)
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <span class="block font-bold text-gray-700 text-xs mb-1">📝 Parciales:</span>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($partials as $p)
                                                    <span class="bg-white border border-gray-200 px-1.5 py-0.5 rounded text-xs text-gray-600 shadow-sm" title="{{ $p->title }}">{{ $p->grade }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                    <span class="text-sm text-gray-500">
                                        Estado
                                    </span>
                                    <div class="flex items-center gap-3">
                                        <span x-text="active ? 'Activa' : 'Inactiva'"
                                            :class="active ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                        </span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" :checked="active" 
                                                @click.prevent="
                                                    Swal.fire({
                                                        title: active ? '¿Archivar asignatura?' : '¿Activar asignatura?',
                                                        text: active ? 'Al archivarla dejarás de verla en el tablero activo principal.' : 'La asignatura volverá a estar disponible como activa.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: active ? '#ef4444' : '#8b5cf6',
                                                        cancelButtonColor: '#9ca3af',
                                                        confirmButtonText: active ? 'Sí, archivar' : 'Sí, activar',
                                                        cancelButtonText: 'Cancelar',
                                                        customClass: { popup: 'font-nunito rounded-2xl shadow-xl border-t-4 ' + (active ? 'border-red-500' : 'border-violet-500'), title: 'font-bold text-gray-800', confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5', cancelButton: 'rounded-lg font-semibold shadow-sm px-5 py-2.5' }
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            let newStatus = !active;
                                                            fetch(`/subjects/{{ $subject->id }}`, {
                                                                method: 'PATCH',
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                                                    'Accept': 'application/json'
                                                                },
                                                                body: JSON.stringify({ is_active: newStatus ? 1 : 0 })
                                                            }).then(() => {
                                                                active = newStatus;
                                                            }).catch(err => { 
                                                                console.error(err); 
                                                            });
                                                        }
                                                    });
                                                "
                                                class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-violet-500 transition-colors duration-300 after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Botones de Acción -->
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between gap-2">
                                    <a href="{{ route('subjects.show', $subject) }}" class="flex items-center gap-1.5 text-sm font-bold text-violet-600 hover:text-violet-800 transition">
                                        Tablero Kanban <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                    <div class="flex gap-2">
                                        <a href="{{ route('subjects.edit', $subject) }}" class="p-1.5 text-gray-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-all" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        <form method="POST" action="{{ route('subjects.destroy', $subject) }}" class="inline-block"
                                            x-data
                                            @submit.prevent="
                                                Swal.fire({
                                                    title: '¿Archivar asignatura?',
                                                    text: 'La asignatura será ocultada de tus vistas.',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#ef4444',
                                                    cancelButtonColor: '#9ca3af',
                                                    confirmButtonText: 'Sí, archivar',
                                                    cancelButtonText: 'Cancelar',
                                                    customClass: { popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-red-500', title: 'font-bold text-gray-800', confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5', cancelButton: 'rounded-lg font-semibold shadow-sm px-5 py-2.5' }
                                                }).then((result) => { if (result.isConfirmed) { $el.submit(); } })
                                            ">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Archivar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL DETALLES MATERIA -->
                        <template x-teleport="body">
                            <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 sm:p-6" style="display:none;">
                                <div @click.away="openModal = false" class="w-full max-w-2xl max-h-[90vh] flex flex-col rounded-3xl bg-white shadow-2xl overflow-hidden font-nunito border-t-8 relative" style="border-top-color: {{ $subject->color_code ?? '#8B5CF6' }}">
                                    
                                    <!-- Botón de cierre flotante siempre visible -->
                                    <button @click="openModal = false" class="absolute top-6 right-6 z-10 text-gray-400 hover:text-gray-700 bg-gray-50 hover:bg-gray-200 rounded-full p-2 transition shadow-sm border border-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>

                                    <!-- Contenido con Scroll -->
                                    <div class="p-6 sm:p-8 overflow-y-auto flex-1">
                                        <div class="mb-6 pr-10">
                                            <h2 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h2>
                                            <p class="text-sm font-semibold text-violet-600 mt-1">{{ $subject->career->name ?? 'Sin carrera asociada' }}</p>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="space-y-4">
                                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Información General</h3>
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center text-violet-600">👨‍🏫</div>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Docente a cargo</p>
                                                                <p class="text-sm font-bold text-gray-800">{{ $subject->teacher ?? 'No asignado' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">📍</div>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Aula / Ubicación</p>
                                                                <p class="text-sm font-bold text-gray-800">{{ $subject->classroom ?? 'No asignada' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-y-4">
                                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Estado Académico</h3>
                                                    <div class="space-y-3">
                                                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                                            <span class="text-sm text-gray-600">Condición</span>
                                                            @if($subject->approval_type)
                                                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold uppercase">{{ $subject->approval_type }}</span>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">No definida</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                                            <span class="text-sm text-gray-600">Nota Final</span>
                                                            @if($subject->final_grade)
                                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">{{ $subject->final_grade }}</span>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">Sin cargar</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($subject->tasks && $subject->tasks->count() > 0)
                                            <div class="mt-6 bg-violet-50 rounded-xl p-5 border border-violet-100">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h3 class="text-sm font-bold text-violet-900">Resumen de Tareas y Exámenes</h3>
                                                    <span class="text-xs font-bold bg-violet-200 text-violet-800 px-2 py-1 rounded-full">{{ $subject->tasks->count() }} registros</span>
                                                </div>
                                                
                                                @php
                                                    $pendingTasks = $subject->tasks->where('status', '!=', 'completed')->count();
                                                    $completedTasks = $subject->tasks->where('status', 'completed')->count();
                                                    $partialsCount = $subject->tasks->where('task_type', 'parcial')->count();
                                                    $finalsCount = $subject->tasks->where('task_type', 'final')->count();
                                                @endphp
                                                
                                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                                    <div class="bg-white p-3 rounded-lg border border-violet-100 text-center shadow-sm">
                                                        <p class="text-2xl font-black text-gray-800">{{ $pendingTasks }}</p>
                                                        <p class="text-[10px] uppercase font-bold text-gray-500">Pendientes</p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded-lg border border-violet-100 text-center shadow-sm">
                                                        <p class="text-2xl font-black text-green-600">{{ $completedTasks }}</p>
                                                        <p class="text-[10px] uppercase font-bold text-gray-500">Completadas</p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded-lg border border-violet-100 text-center shadow-sm">
                                                        <p class="text-2xl font-black text-orange-500">{{ $partialsCount }}</p>
                                                        <p class="text-[10px] uppercase font-bold text-gray-500">Parciales</p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded-lg border border-violet-100 text-center shadow-sm">
                                                        <p class="text-2xl font-black text-red-500">{{ $finalsCount }}</p>
                                                        <p class="text-[10px] uppercase font-bold text-gray-500">Finales</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-8 flex gap-3">
                                            <a href="{{ route('subjects.show', $subject) }}" class="flex-1 bg-violeta-moderno hover:bg-violet-700 text-white font-bold py-3 px-4 rounded-xl text-center shadow-md transition hover:-translate-y-0.5">
                                                Ir al Tablero Kanban
                                            </a>
                                            <a href="{{ route('subjects.edit', $subject) }}" class="px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-center transition">
                                                Editar Materia
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                        <span class="text-5xl mb-4 block">📚</span>
                        @if($isActive)
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes materias activas</h3>
                            <p class="text-gray-500 mb-6">Aún no has agregado ninguna materia a tu plan de estudios.</p>
                            <a href="{{ route('subjects.create') }}" class="inline-block bg-violeta-moderno hover:bg-opacity-90 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md transition-all">
                                Agregar mi primera materia
                            </a>
                        @else
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No hay materias archivadas</h3>
                            <p class="text-gray-500">Aquí aparecerán las materias que marques como inactivas.</p>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
