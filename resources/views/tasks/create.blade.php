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

                <form class="mt-8" x-data="{ taskType: 'normal', priority: 'low' }">
                    <div class="grid gap-8 lg:grid-cols-2">

                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Título de la tarea <span class="text-red-500">*</span>
                                </label>

                                <input id="title" type="text" name="title"
                                    placeholder="Ej: Estudiar para el parcial de Algoritmos"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:ring-violeta-moderno">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>

                                <textarea id="description" name="description" rows="5"
                                    placeholder="Agregá una descripción detallada..."
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:ring-violeta-moderno"></textarea>
                            </div>

                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Materia activa <span class="text-red-500">*</span>
                                </label>

                                <select id="subject_id" name="subject_id"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno">
                                    <option value="">Seleccionar materia</option>
                                    <option value="1">Algoritmos</option>
                                    <option value="3">Web Development</option>
                                </select>

                                <p class="mt-2 text-xs text-gray-400">
                                    Solo se muestran materias activas del usuario.
                                </p>
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

                                <input id="due_date" type="date" name="due_date"
                                    class="w-full rounded-xl border border-violet-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno">
                            </div>

                            <div>
                                <label for="task_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora opcional
                                </label>

                                <input id="task_time" type="time" name="task_time"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno">
                            </div>

                            <div>
                                <label for="estimated_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tiempo estimado
                                </label>

                                <input id="estimated_time" type="text" name="estimated_time" placeholder="Ej: 2h 30m"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:ring-violeta-moderno">
                            </div>

                            <div>
                                <label for="reminder" class="block text-sm font-medium text-gray-700 mb-2">
                                    Recordatorio
                                </label>

                                <select id="reminder" name="reminder"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-700 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno">
                                    <option>Sin recordatorio</option>
                                    <option>1 hora antes</option>
                                    <option>1 día antes</option>
                                    <option>1 semana antes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                        <button type="button"
                            class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                            Cancelar
                        </button>

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