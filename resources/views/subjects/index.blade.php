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
