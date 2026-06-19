@props(['title', 'subject', 'type', 'priority', 'description' => '', 'dueDate' => ''])

@php
    $typeColors = [
        'normal' => 'bg-gray-100 text-gray-700 border-gray-200',
        'tp' => 'bg-blue-100 text-blue-700 border-blue-200',
        'parcial' => 'bg-orange-100 text-orange-700 border-orange-200',
        'final' => 'bg-red-100 text-red-700 border-red-200',
    ];

    $priorityColors = [
        'low' => 'bg-green-100 text-green-700',
        'medium' => 'bg-orange-100 text-orange-700',
        'high' => 'bg-red-100 text-red-700',
    ];
@endphp

<div x-data="{ open: false }">

    <div @click="open = true"
        class="cursor-pointer rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

        <div class="flex items-center justify-between">

            <span class="rounded-full px-3 py-1 text-xs font-semibold border {{ $typeColors[$type] }}">
                {{ strtoupper($type) }}
            </span>

            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityColors[$priority] }}">
                {{ strtoupper($priority) }}
            </span>

        </div>

        <h3 class="mt-4 text-lg font-bold text-gray-800">
            {{ $title }}
        </h3>

        <p class="mt-2 text-sm text-gray-500">
            {{ $subject }}
        </p>

        <div class="mt-4 flex items-center justify-between text-sm text-gray-400">
            <span>📅 {{ $dueDate }}</span>
            <span>Ver detalles →</span>
        </div>
    </div>

    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        style="display:none;">

        <div @click.away="open = false" class="w-full max-w-2xl rounded-3xl bg-white p-8 shadow-2xl">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-violeta-moderno">
                    {{ $subject }}
                </p>

                <h2 class="mt-1 text-2xl font-bold text-gray-800">
                    {{ $title }}
                </h2>

                <button @click="open = false" class="text-gray-400 hover:text-gray-700">
                    ✕
                </button>

            </div>

            <div class="mt-6 flex flex-wrap gap-3">

                <span class="rounded-full px-3 py-1 text-sm font-semibold border {{ $typeColors[$type] }}">
                    {{ strtoupper($type) }}
                </span>

                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $priorityColors[$priority] }}">
                    {{ strtoupper($priority) }}
                </span>

            </div>

            <div class="mt-6 space-y-4">

                <div>
                    <h3 class="font-semibold text-gray-700">
                        Materia
                    </h3>

                    <p class="text-gray-500">
                        {{ $subject }}
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">
                        Fecha límite
                    </h3>

                    <p class="text-gray-500">
                        {{ $dueDate }}
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">
                        Descripción
                    </h3>

                    <p class="text-gray-500">
                        {{ $description ?: 'Sin descripción disponible.' }}
                    </p>
                </div>

            </div>

            <div class="mt-8 flex justify-end">

                <button @click="open = false"
                    class="rounded-xl bg-violeta-moderno px-5 py-3 text-white font-semibold shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                    Cerrar
                </button>

            </div>

        </div>

    </div>

</div>
