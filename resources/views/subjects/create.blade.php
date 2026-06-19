<x-app-layout>
    <div class="py-6">
        <div class="max-w-sm mx-auto sm:max-w-md">
            <div class="bg-white shadow-md rounded-xl p-4 sm:p-6">
                
                <!-- Encabezado centrado -->
                <div class="text-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 font-nunito">
                        Registrar nueva asignatura
                    </h2>
                    <p class="mt-1 text-gray-500 font-nunito text-sm">
                        Completa los datos para agregar una nueva asignatura
                    </p>
                </div>

                <form method="POST" action="{{ route('subjects.store') }}" class="space-y-4">
                    @csrf
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Nombre de la asignatura
                        </label>
                        <input type="text" id="nombre" name="name" required
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                    </div>

                    <!-- Docente (Opcional) -->
                    <div>
                        <label for="teacher" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Docente <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="text" id="teacher" name="teacher" placeholder="Ej: Ing. Juan López"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                    </div>

                    <!-- Aula (Opcional) -->
                    <div>
                        <label for="classroom" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Aula <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="text" id="classroom" name="classroom" placeholder="Ej: Aula 12, Piso 2"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                    </div>

                    <!-- Carrera custom dropdown -->
                    <div x-data="{ open: false, selected: 'Seleccione una carrera', selectedId: '' }" class="relative">
                        <label class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Carrera
                        </label>
                        <!-- Botón principal -->
                        <button type="button" @click="open = !open"
                                class="w-full flex justify-between items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm font-nunito
                                       focus:border-violet-400 focus:ring-violet-400 transition">
                            <span x-text="selected"></span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Opciones -->
                        <div x-show="open" @click.away="open = false"
                             class="absolute mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg z-20">
                            <ul class="rounded-lg overflow-hidden">
                                @foreach($careers as $career)
                                <li @click="selected = '{{ $career->name }}'; selectedId = '{{ $career->id }}'; open = false"
                                    class="px-3 py-2 hover:bg-violet-100 cursor-pointer text-sm font-nunito">
                                    {{ $career->name }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Input oculto para enviar el valor -->
                        <input type="hidden" name="career_id" :value="selectedId" required>
                    </div>

                    <!-- Color identificador (sin hexadecimal visible) -->
                    <div>
                        <div class="flex items-center gap-2">
                            <label for="color_identificador" class="text-sm font-bold text-gray-700 font-nunito">
                                Color
                            </label>
                            <div class="relative w-6 h-6">
                                <input type="color" id="color_identificador" name="color_code" value="#8B5CF6" required
                                       class="absolute top-0 left-0 w-6 h-6 opacity-0 cursor-pointer z-10"
                                       onchange="document.getElementById('color_preview').style.backgroundColor=this.value">
                                <div id="color_preview"
                                     class="w-6 h-6 rounded-md border shadow-sm absolute top-0 left-0 z-0"
                                     style="background-color: #000000"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón -->
                    <div>
                        <button type="submit"
                                class="w-full rounded-lg bg-violet-600 px-4 py-2 font-semibold text-white shadow-sm transition
                                hover:bg-violet-700 hover:-translate-y-0.5 hover:shadow-md font-nunito text-sm">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>





