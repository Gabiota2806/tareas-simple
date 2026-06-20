<x-app-layout>
    <div class="font-nunito bg-[#F8FAFC] min-h-screen text-[#1E293B]">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 lg:pb-12">

            <!-- Header del dashboard -->
            <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                @php
                    $activeUniId = session('active_university_id');
                    
                    if (request()->has('career_id')) {
                        session(['dashboard_career_id' => request('career_id')]);
                    }
                    $selectedCareer = session('dashboard_career_id');

                    $careers = \App\Models\Career::where('university_id', $activeUniId)
                        ->whereHas('university', fn($q) => $q->where('user_id', Auth::id()))
                        ->orderBy('name')
                        ->get();

                    $subjectsQuery = \App\Models\Subject::where('user_id', Auth::id())
                        ->where('is_active', true)
                        ->whereHas('career', fn($q) => $q->where('university_id', $activeUniId));
                    
                    if ($selectedCareer) {
                        $subjectsQuery->where('career_id', $selectedCareer);
                    }

                    $activeSubjects = $subjectsQuery->get();
                @endphp

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Resumen general
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Visualizá tus materias activas y tareas según la carrera seleccionada.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="w-full sm:w-56">
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

                    <a href="{{ route('tasks.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-violeta-moderno px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md w-full sm:w-auto">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva tarea
                    </a>
                </div>
            </section>

            <!-- ================= GRID DE MATERIAS ================= -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @forelse($activeSubjects as $subject)
                    <div class="rounded-2xl border-l-4 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md flex flex-col justify-between" style="border-left-color: {{ $subject->color_code }}">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="font-bold text-gray-800">
                                    {{ $subject->name }}
                                </h4>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $subject->tasks()->active()->where('status', '!=', 'completed')->count() }} tareas pendientes
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('subjects.show', $subject->id) }}" class="inline-flex items-center text-sm font-semibold text-violeta-moderno hover:underline">
                            Ver tablero Kanban →
                        </a>
                    </div>
                @empty
                    <div class="col-span-full p-8 text-center bg-white rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-gray-500">No tienes materias activas. ¡Comienza creando una!</p>
                        <a href="{{ route('subjects.create') }}" class="mt-4 inline-block bg-violet-100 text-violet-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-violet-200">
                            Crear materia
                        </a>
                    </div>
                @endforelse
            </div>
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
