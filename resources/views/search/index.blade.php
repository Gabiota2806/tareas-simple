<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Resultados de búsqueda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">
                    Buscando: <span class="text-violeta-moderno">"{{ $query }}"</span>
                </h3>
            </div>

            @if(!$query)
                <div class="rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm">
                    <p class="text-gray-500">Escribe algo en el buscador para encontrar tareas o materias.</p>
                </div>
            @elseif($tasks->isEmpty() && $subjects->isEmpty())
                <div class="rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm">
                    <p class="text-gray-500">No se encontraron resultados para "{{ $query }}".</p>
                </div>
            @else

                <!-- Resultados de Tareas -->
                @if($tasks->isNotEmpty())
                    <h4 class="mb-4 text-lg font-bold text-gray-700">Tareas encontradas ({{ $tasks->count() }})</h4>
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tasks as $task)
                            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h5 class="font-bold text-gray-800">
                                            {{ $task->title }}
                                        </h5>
                                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                            {{ $task->description ?: 'Sin descripción' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-violet-100 px-2 py-1 text-xs font-semibold text-violet-700">
                                        {{ $task->subject->name ?? 'Sin materia' }}
                                    </span>
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Resultados de Materias -->
                @if($subjects->isNotEmpty())
                    <h4 class="mb-4 text-lg font-bold text-gray-700">Materias encontradas ({{ $subjects->count() }})</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($subjects as $subject)
                            <div class="rounded-2xl border-l-4 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md flex flex-col justify-between" style="border-left-color: {{ $subject->color_code }}">
                                <div>
                                    <h4 class="font-bold text-gray-800">
                                        {{ $subject->name }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $subject->teacher ?: 'Sin profesor' }}
                                    </p>
                                </div>
                                <a href="{{ route('subjects.show', $subject->id) }}" class="mt-4 inline-flex items-center text-sm font-semibold text-violeta-moderno hover:underline">
                                    Ir a la materia →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

            @endif

        </div>
    </div>
</x-app-layout>
