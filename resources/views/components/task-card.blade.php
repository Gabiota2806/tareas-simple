@props(['id', 'status', 'title', 'subject', 'type', 'priority', 'description' => '', 'dueDate' => '', 'rawDueDate' => ''])

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

<div x-data="{ 
    open: false, 
    isEditing: false,
    currentStatus: '{{ $status }}',
    editTitle: '{{ addslashes($title) }}',
    editDescription: '{{ str_replace(["\r", "\n"], ['\r', '\n'], addslashes($description)) }}',
    editType: '{{ $type }}',
    editPriority: '{{ $priority }}',
    editDueDate: '{{ $rawDueDate }}',
    
    async saveChanges() {
        try {
            const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');
            await fetch(`/tasks/{{ $id }}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({ 
                    title: this.editTitle, 
                    description: this.editDescription,
                    task_type: this.editType,
                    priority: this.editPriority,
                    due_date: this.editDueDate || null
                })
            });
            location.reload();
        } catch (err) { console.error(err); }
    }
}">

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

    <template x-teleport="body">
        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display:none;">

            <div @click.away="open = false" class="w-full max-w-2xl rounded-3xl bg-white p-8 shadow-2xl">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-violeta-moderno">
                    {{ $subject }}
                </p>

                <div class="flex items-center gap-2">
                    <button @click="isEditing = !isEditing" type="button" class="text-xs font-bold text-gray-500 hover:text-violet-600 border border-gray-200 bg-white rounded-lg py-1.5 px-3 shadow-sm transition">
                        <span x-show="!isEditing">✏️ Editar</span>
                        <span x-show="isEditing" style="display:none;">❌ Cancelar</span>
                    </button>

                    <!-- Select de estado reactivo (Jira style) -->
                    <div x-show="!isEditing" x-data="{ 
                        dropdownOpen: false,
                        statuses: {
                            'pending': '📋 Pendiente',
                            'in_progress': '⚡ En progreso',
                            'completed': '✅ Completada'
                        },
                        changeStatus(status) {
                            this.currentStatus = status;
                            this.dropdownOpen = false;
                            fetch(`/tasks/{{ $id }}`, {
                                method: 'PATCH',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Accept': 'application/json' },
                                body: JSON.stringify({ status: status })
                            }).then(() => {
                                let targetColId = 'col-pendiente';
                                if (status === 'in_progress') targetColId = 'col-proceso';
                                if (status === 'completed') targetColId = 'col-completada';
                                
                                const cardElement = document.querySelector(`[data-id='{{ $id }}']`);
                                if(cardElement && document.getElementById(targetColId)) {
                                    document.getElementById(targetColId).appendChild(cardElement);
                                    
                                    document.getElementById('count-pendiente').innerText = document.getElementById('col-pendiente').children.length;
                                    document.getElementById('count-proceso').innerText = document.getElementById('col-proceso').children.length;
                                    document.getElementById('count-completada').innerText = document.getElementById('col-completada').children.length;
                                }
                            }).catch(err => console.error(err));
                        }
                    }" class="relative">
                        <button type="button" @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center justify-between gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-bold text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno w-36">
                            <span x-text="statuses[currentStatus]"></span>
                            <svg class="h-3 w-3 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                            class="absolute z-50 mt-1 w-full rounded-lg border border-gray-100 bg-white p-2 shadow-xl"
                            style="display:none;">
                            <template x-for="(label, key) in statuses" :key="key">
                                <button type="button" @click="changeStatus(key)"
                                    class="w-full text-left truncate rounded-md px-3 py-1.5 text-xs hover:bg-violet-50 transition"
                                    :class="currentStatus === key ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                    x-text="label">
                                </button>
                            </template>
                        </div>
                    </div>

                    <button @click="open = false" class="text-gray-400 hover:text-gray-700 text-xl leading-none ml-2">
                        &times;
                    </button>
                </div>
            </div>

            <!-- MODO LECTURA -->
            <div x-show="!isEditing">
                <h2 class="mt-4 text-2xl font-bold text-gray-800">
                    {{ $title }}
                </h2>

                <div class="mt-4 flex flex-wrap gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold border {{ $typeColors[$type] }}">
                        {{ strtoupper($type) }}
                    </span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityColors[$priority] }}">
                        {{ strtoupper($priority) }}
                    </span>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-700 text-sm">Fecha límite</h3>
                        <p class="text-gray-500 text-sm">{{ $dueDate }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700 text-sm">Descripción</h3>
                        <div class="text-gray-600 text-sm whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100 mt-1">
                            {{ $description ?: 'Sin descripción disponible.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODO EDICIÓN -->
            <div x-show="isEditing" style="display:none;" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Título</label>
                    <input type="text" x-model="editTitle" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm">
                </div>
                
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de Tarea</label>
                        <div x-data="{ open: false, options: {'normal': 'Normal', 'tp': 'Trabajo Práctico', 'parcial': 'Parcial', 'final': 'Final'} }" class="relative w-full">
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:bg-white">
                                <span x-text="options[editType]"></span>
                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-1 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl" style="display:none;">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button" @click="editType = key; open = false"
                                        class="w-full text-left truncate rounded-lg px-3 py-2 text-sm hover:bg-violet-50 transition"
                                        :class="editType === key ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                        x-text="label">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Prioridad</label>
                        <div x-data="{ open: false, options: {'low': 'Baja', 'medium': 'Media', 'high': 'Alta'} }" class="relative w-full">
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:bg-white">
                                <span x-text="options[editPriority]"></span>
                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-1 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl" style="display:none;">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button" @click="editPriority = key; open = false"
                                        class="w-full text-left truncate rounded-lg px-3 py-2 text-sm hover:bg-violet-50 transition"
                                        :class="editPriority === key ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                        x-text="label">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Fecha límite</label>
                    <input type="date" x-model="editDueDate" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Descripción</label>
                    <textarea x-model="editDescription" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm"></textarea>
                </div>
                
                <div class="flex justify-end pt-4 mt-2 border-t border-gray-100">
                    <button @click="saveChanges()" class="rounded-xl bg-violeta-moderno px-6 py-2.5 text-white font-bold shadow-md transition hover:-translate-y-0.5 hover:shadow-lg text-sm flex items-center gap-2">
                        💾 Guardar cambios
                    </button>
                </div>
            </div>

            </div>

        </div>
    </template>

</div>
