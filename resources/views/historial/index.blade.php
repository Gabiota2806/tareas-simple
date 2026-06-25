<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Historial de Tareas
        </h2>
        <p class="text-sm text-gray-500 mt-1">Acá podés ver todas las tareas y exámenes que ya completaste. Permanecerán guardados acá como registro de tu avance.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($tasks->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Todavía no hay tareas en el historial</h3>
                    <p class="mt-2 text-gray-500 max-w-md mx-auto">A medida que vayas completando tus trabajos y rindiendo exámenes, aparecerán acá para que no los pierdas de vista.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tasks as $task)
                        <x-task-card 
                            id="{{ $task->id }}"
                            status="{{ $task->status }}"
                            title="{{ $task->title }}"
                            subject="{{ $task->subject->name ?? 'Sin Asignatura' }}"
                            subjectId="{{ $task->subject_id }}"
                            type="{{ $task->task_type }}"
                            priority="{{ $task->priority }}"
                            description="{{ $task->description }}"
                            dueDate="{{ $task->due_date ? $task->due_date->format('d M') : 'Sin fecha' }}"
                            rawDueDate="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                            teamMembers="{{ $task->team_members }}"
                            submissionFormat="{{ $task->submission_format }}"
                            grade="{{ $task->grade }}"
                            enrollmentDate="{{ $task->enrollment_date ? $task->enrollment_date->format('Y-m-d') : '' }}"
                            examType="{{ $task->exam_type }}"
                            :subtasks="$task->nestedSubtasks ?? collect()"
                        />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
