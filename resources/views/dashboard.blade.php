<x-app-layout>
    <div class="font-nunito bg-[#F8FAFC] min-h-screen text-[#1E293B]">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 lg:pb-12">

            <!-- Header del dashboard -->
            <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Resumen general
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Visualizá tus materias, tareas y próximas entregas según la universidad y carrera seleccionadas.
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

            <!-- Cards resumen -->
            <section class="mb-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Materias activas</p>
                    <p class="mt-3 text-3xl font-bold text-violeta-moderno">5</p>
                    <p class="mt-1 text-xs text-gray-400">Según la carrera seleccionada</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Tareas pendientes</p>
                    <p class="mt-3 text-3xl font-bold text-orange-500">
                        {{ $tasks->where('is_completed', false)->count() }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Actividades por resolver</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Completadas</p>
                    <p class="mt-3 text-3xl font-bold text-green-600">
                        {{ $tasks->where('is_completed', true)->count() }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Tareas finalizadas</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Próximas entregas</p>
                    <p class="mt-3 text-3xl font-bold text-red-500">
                        {{ $tasks->where('is_completed', false)->whereNotNull('due_date')->count() }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Con fecha asignada</p>
                </div>
            </section>

            <!-- Materias -->
            <section class="mb-10">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Materias destacadas
                        </h3>

                        <p class="text-sm text-gray-500">
                            Desde cada materia se podrá acceder al tablero de tareas correspondiente.
                        </p>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                    <div
                        class="rounded-2xl border-l-4 border-violet-500 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800">
                                    Programación
                                </h4>

                                <p class="mt-1 text-sm text-gray-500">
                                    4 tareas pendientes
                                </p>
                            </div>

                        </div>

                        <a href="{{ route('subjects.index') }}"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-violeta-moderno hover:underline">
                            Ver tablero →
                        </a>
                    </div>

                    <div
                        class="rounded-2xl border-l-4 border-green-500 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800">
                                    Base de Datos
                                </h4>

                                <p class="mt-1 text-sm text-gray-500">
                                    2 tareas pendientes
                                </p>
                            </div>

                        </div>

                        <a href="{{ route('subjects.index') }}"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-violeta-moderno hover:underline">
                            Ver tablero →
                        </a>
                    </div>

                    <div
                        class="rounded-2xl border-l-4 border-orange-500 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800">
                                    Inglés
                                </h4>

                                <p class="mt-1 text-sm text-gray-500">
                                    1 tarea pendiente
                                </p>
                            </div>

                        </div>

                        <a href="{{ route('subjects.index') }}"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-violeta-moderno hover:underline">
                            Ver tablero →
                        </a>
                    </div>

                </div>
            </section>

        </main>

        <!-- Botón flotante móvil -->
        <div class="fixed bottom-6 right-6 z-50 lg:hidden">
            <a href="{{ route('tasks.create') }}"
                class="flex h-14 w-14 items-center justify-center rounded-xl bg-violeta-moderno text-2xl font-bold text-white shadow-lg transition hover:bg-opacity-90 active:scale-95">
                +
            </a>
        </div>
    </div>

</x-app-layout>
