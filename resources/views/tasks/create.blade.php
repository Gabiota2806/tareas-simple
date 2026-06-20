<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva tarea
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md p-8">
                <h1 class="text-2xl font-bold text-gray-800">
                    Registrar nueva tarea
                </h1>

                <p class="mt-2 text-gray-500">
                    Completá los datos principales para organizar tu actividad académica.
                </p>

                <form method="POST" action="{{ route('tasks.store') }}" class="mt-8" x-data="{ taskType: '{{ old('task_type', 'normal') }}', priority: '{{ old('priority', 'low') }}' }">
                    @csrf
                    <div class="grid gap-8 lg:grid-cols-2">

                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Título de la tarea <span class="text-red-500">*</span>
                                </label>

                                <input id="title" type="text" name="title" value="{{ old('title') }}" required
                                    placeholder="Ej: Estudiar para el parcial de Algoritmos"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:ring-violeta-moderno">
                                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>

                                <textarea id="description" name="description" rows="5"
                                    placeholder="Agregá una descripción detallada..."
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:ring-violeta-moderno">{{ old('description') }}</textarea>
                                @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Materia activa <span class="text-red-500">*</span>
                                </label>

                                <div x-data="{ open: false, selected: '{{ old('subject_id') }}', get selectedName() {
                                    const subjects = @json($subjects->mapWithKeys(fn($s) => [$s->id => $s->name]));
                                    return subjects[this.selected] || 'Seleccionar materia';
                                } }" class="relative w-full">
                                    <input type="hidden" name="subject_id" x-model="selected" required>
                                    
                                    <button type="button" @click="open = !open"
                                        class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:border-violeta-moderno">
                                        
                                        <span x-text="selectedName" class="truncate flex-1 text-left" :class="selected === '' ? 'text-gray-400' : 'text-gray-700'"></span>

                                        <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute z-50 mt-2 w-full max-h-60 overflow-y-auto rounded-xl border border-gray-100 bg-white p-2 shadow-xl"
                                        style="display:none;">
                                        
                                        <button type="button" @click="selected = ''; open = false" 
                                            class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                            :class="selected === '' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'">
                                            Seleccionar materia
                                        </button>

                                        @foreach($subjects as $subject)
                                            <button type="button" @click="selected = '{{ $subject->id }}'; open = false" 
                                                class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                                :class="selected === '{{ $subject->id }}' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'">
                                                {{ $subject->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                @error('subject_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror

                                @if($subjects->isEmpty())
                                <p class="mt-2 text-xs text-red-400 font-medium">
                                    No tienes materias activas. <a href="{{ route('subjects.create') }}" class="underline hover:text-red-500">Crear una materia</a>.
                                </p>
                                @else
                                <p class="mt-2 text-xs text-gray-400">
                                    Solo se muestran materias activas del usuario.
                                </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Tipo de tarea <span class="text-red-500">*</span>
                                </label>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <label
                                        :class="taskType === 'normal'
                                            ? 'border-violet-300 bg-violet-50 text-violet-700'
                                            : 'border-gray-200 text-gray-600 hover:border-violet-300 hover:bg-violet-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-sm font-medium transition">
                                        <input type="radio" name="task_type" value="normal" x-model="taskType"
                                            class="sr-only">
                                        Tarea normal
                                    </label>

                                    <label
                                        :class="taskType === 'tp'
                                            ? 'border-violet-300 bg-violet-50 text-violet-700'
                                            : 'border-gray-200 text-gray-600 hover:border-violet-300 hover:bg-violet-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-sm font-medium transition">
                                        <input type="radio" name="task_type" value="tp" x-model="taskType"
                                            class="sr-only">
                                        Trabajo práctico
                                    </label>

                                    <label
                                        :class="taskType === 'parcial'
                                            ? 'border-violet-300 bg-violet-50 text-violet-700'
                                            : 'border-gray-200 text-gray-600 hover:border-violet-300 hover:bg-violet-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-sm font-medium transition">
                                        <input type="radio" name="task_type" value="parcial" x-model="taskType"
                                            class="sr-only">
                                        Parcial
                                    </label>

                                    <label
                                        :class="taskType === 'final'
                                            ? 'border-violet-300 bg-violet-50 text-violet-700'
                                            : 'border-gray-200 text-gray-600 hover:border-violet-300 hover:bg-violet-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-sm font-medium transition">
                                        <input type="radio" name="task_type" value="final" x-model="taskType"
                                            class="sr-only">
                                        Final
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Prioridad
                                </label>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <label
                                        :class="priority === 'low'
                                            ? 'border-green-300 bg-green-50 text-green-700'
                                            : 'border-gray-200 text-gray-600 hover:bg-green-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-center text-sm font-medium transition">
                                        <input type="radio" name="priority" value="low" x-model="priority"
                                            class="sr-only">
                                        Baja
                                    </label>

                                    <label
                                        :class="priority === 'medium'
                                            ? 'border-orange-300 bg-orange-50 text-orange-700'
                                            : 'border-gray-200 text-gray-600 hover:bg-orange-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-center text-sm font-medium transition">
                                        <input type="radio" name="priority" value="medium" x-model="priority"
                                            class="sr-only">
                                        Media
                                    </label>

                                    <label
                                        :class="priority === 'high'
                                            ? 'border-red-300 bg-red-50 text-red-700'
                                            : 'border-gray-200 text-gray-600 hover:bg-red-50'"
                                        class="cursor-pointer rounded-xl border px-4 py-3 text-center text-sm font-medium transition">
                                        <input type="radio" name="priority" value="high" x-model="priority"
                                            class="sr-only">
                                        Alta
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de vencimiento
                                </label>

                                <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}"
                                    class="w-full rounded-xl border border-violet-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno">
                                @error('due_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="task_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora opcional
                                </label>

                                <input id="task_time" type="time" name="task_time" value="{{ old('task_time') }}"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno">
                                @error('task_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="estimated_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tiempo estimado
                                </label>

                                <input id="estimated_time" type="number" min="1" name="estimated_time" value="{{ old('estimated_time') }}" placeholder="Ej: 120 (en minutos)"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:ring-violeta-moderno">
                                @error('estimated_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="reminder" class="block text-sm font-medium text-gray-700 mb-2">
                                    Recordatorio
                                </label>

                                <div x-data="{ open: false, selected: '{{ old('reminder', '0') }}', get selectedName() {
                                    return this.selected === '1' ? 'Sí, enviar alerta al correo' : 'No';
                                } }" class="relative w-full">
                                    <input type="hidden" name="reminder" x-model="selected">
                                    
                                    <button type="button" @click="open = !open"
                                        class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:border-violeta-moderno">
                                        
                                        <span x-text="selectedName" class="truncate flex-1 text-left"></span>

                                        <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute z-50 mt-2 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl"
                                        style="display:none;">
                                        
                                        <button type="button" @click="selected = '0'; open = false" 
                                            class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                            :class="selected === '0' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'">
                                            No
                                        </button>

                                        <button type="button" @click="selected = '1'; open = false" 
                                            class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                            :class="selected === '1' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'">
                                            Sí, enviar alerta al correo
                                        </button>
                                    </div>
                                </div>
                                @error('reminder') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                        <a href="{{ route('dashboard') }}"
                            class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="rounded-xl bg-violeta-moderno px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                            Crear tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>