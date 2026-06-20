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
                    <div x-data="{ active: {{ $subject->is_active ? 'true' : 'false' }}, tabIsActive: {{ $isActive ? 'true' : 'false' }} }"
                        x-show="active === tabIsActive"
                        x-transition.opacity.duration.300ms
                        :class="active ? 'bg-white border-violet-500' : 'bg-gray-100 border-gray-400 opacity-75'"
                        class="rounded-2xl shadow-md p-5 border-t-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                        style="border-top-color: {{ $subject->color_code ?? '#8B5CF6' }}">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            {{ $subject->name }}
                        </h2>

                        <div class="space-y-1 text-gray-600">
                            <p>📍 Aula {{ $subject->classroom ?? 'No asignada' }}</p>
                            <p>👨‍🏫 Docente: {{ $subject->teacher ?? 'Sin asignar' }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    Estado
                                </span>
                                <div class="flex items-center gap-3">
                                    <span x-text="active ? 'Activa' : 'Inactiva'"
                                        :class="active ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                    </span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="active" 
                                            @change="
                                                fetch(`/subjects/{{ $subject->id }}`, {
                                                    method: 'PATCH',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                                        'Accept': 'application/json'
                                                    },
                                                    body: JSON.stringify({ is_active: active ? 1 : 0 })
                                                }).catch(err => { 
                                                    active = !active; // Revertir visualmente si hay error
                                                    console.error(err); 
                                                });
                                            "
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-300 rounded-full
                                                    peer-checked:bg-violet-500
                                                    transition-colors duration-300
                                                    after:content-['']
                                                    after:absolute
                                                    after:top-[2px]
                                                    after:left-[2px]
                                                    after:bg-white
                                                    after:border
                                                    after:rounded-full
                                                    after:h-5
                                                    after:w-5
                                                    after:transition-all
                                                    peer-checked:after:translate-x-full">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-2">
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
                                            customClass: {
                                                popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-red-500',
                                                title: 'font-bold text-gray-800',
                                                confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5',
                                                cancelButton: 'rounded-lg font-semibold shadow-sm px-5 py-2.5'
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $el.submit();
                                            }
                                        })
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
