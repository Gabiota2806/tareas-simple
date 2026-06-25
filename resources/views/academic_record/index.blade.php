<x-app-layout>
    <div class="font-nunito bg-gray-100 min-h-screen text-[#1E293B]">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 lg:pb-12">

            <!-- Header -->
            <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">
                        Libreta Universitaria
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Historial de exámenes, finales y calificaciones.
                    </p>
                </div>

                <!-- Career Filter -->
                <div class="flex items-center gap-4">
                    <form method="GET" action="{{ route('academic-record.index') }}" class="w-full sm:w-56">
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
                </div>
            </section>

            <!-- Contenido -->
            <div class="space-y-8">
                @forelse($subjects as $subject)
                    <div class="bg-white rounded-3xl shadow-md border-t-8 border-x border-b border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg" style="border-top-color: {{ $subject->color_code ?? '#8B5CF6' }}">
                        <div class="bg-white border-b border-gray-100 px-6 py-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <span class="w-4 h-4 mt-1.5 rounded-full shadow-sm shrink-0" style="background-color: {{ $subject->color_code }}"></span>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $subject->name }}</h3>
                                    
                                    @php
                                        $partials = $subject->tasks->where('task_type', 'parcial')->whereNotNull('grade');
                                    @endphp
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @if($subject->approval_type)
                                            <span class="text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $subject->approval_type }}</span>
                                        @else
                                            <span class="text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Sin definir</span>
                                        @endif

                                        @if($subject->final_grade)
                                            <span class="text-xs font-bold bg-green-100 text-green-800 border border-green-200 px-2 py-0.5 rounded-md">NOTA FINAL: {{ $subject->final_grade }}</span>
                                        @endif
                                        
                                        @if($partials->count() > 0)
                                            <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium ml-1 lg:ml-2 lg:border-l lg:border-gray-300 lg:pl-2">
                                                @foreach($partials as $p)
                                                    <span class="bg-white px-2 py-0.5 rounded border border-gray-200 shadow-sm">{{ $p->title }}: <strong class="text-gray-800">{{ $p->grade }}</strong></span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @php
                                $examCount = $subject->tasks->count();
                                $completedCount = $subject->tasks->where('status', 'completed')->count();
                                $avgGrade = $completedCount > 0 ? number_format($subject->tasks->where('status', 'completed')->avg('grade'), 2) : '-';
                            @endphp
                            <div class="flex items-center gap-3 text-sm font-medium text-gray-500 shrink-0">
                                <span class="bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">{{ $examCount }} Evaluaciones</span>
                                <span class="bg-violet-50 text-violet-700 px-3 py-1 rounded-full border border-violet-100 font-bold shadow-sm">Promedio: {{ $avgGrade }}</span>
                            </div>
                        </div>
                        
                        <div class="p-6 sm:p-8 bg-slate-50/80">
                            @if($subject->tasks->isEmpty())
                                <div class="text-center py-10">
                                    <p class="text-sm text-gray-500 mb-4">No hay evaluaciones registradas para esta materia.</p>
                                    <a href="{{ route('tasks.create', ['subject_id' => $subject->id, 'allowed_types' => 'exams']) }}" class="inline-flex items-center justify-center gap-2 text-sm font-bold text-violet-700 bg-violet-50 hover:bg-violet-100 hover:text-violet-800 px-5 py-2.5 rounded-xl transition-all shadow-sm group">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Registrar mi primer examen
                                    </a>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    @foreach($subject->tasks as $exam)
                                        <x-task-card 
                                            id="{{ $exam->id }}"
                                            status="{{ $exam->status }}"
                                            title="{{ $exam->title }}"
                                            subject="{{ $subject->name }}"
                                            subjectId="{{ $subject->id }}"
                                            type="{{ $exam->task_type }}"
                                            priority="{{ $exam->priority }}"
                                            description="{{ $exam->description }}"
                                            dueDate="{{ $exam->due_date ? $exam->due_date->format('d M') : 'Sin fecha' }}"
                                            rawDueDate="{{ $exam->due_date ? $exam->due_date->format('Y-m-d') : '' }}"
                                            teamMembers="{{ $exam->team_members }}"
                                            submissionFormat="{{ $exam->submission_format }}"
                                            grade="{{ $exam->grade }}"
                                            enrollmentDate="{{ $exam->enrollment_date ? $exam->enrollment_date->format('Y-m-d') : '' }}"
                                            examType="{{ $exam->exam_type }}"
                                            :subtasks="$exam->nestedSubtasks ?? collect()"
                                        />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center p-12 bg-white rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-gray-500 text-lg">No tienes materias activas con evaluaciones.</p>
                    </div>
                @endforelse
            </div>

        </main>
    </div>
</x-app-layout>
