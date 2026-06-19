<x-app-layout>

    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                Mis Materias
            </h1>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @forelse($subjects as $subject)
                    <div x-data="{ active: {{ $subject->is_active ? 'true' : 'false' }} }"
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
                                        <input type="checkbox" x-model="active" class="sr-only peer">
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
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes materias registradas</h3>
                        <p class="text-gray-500 mb-6">Aún no has agregado ninguna materia a tu plan de estudios.</p>
                        <a href="{{ route('subjects.create') }}" class="inline-block bg-violeta-moderno hover:bg-opacity-90 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md transition-all">
                            Agregar mi primera materia
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
