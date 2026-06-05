<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Subjects</title>
</head>

<body>

    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                Mis Materias
            </h1>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div x-data="{ active: true }"
                    :class="active ? 'bg-white border-violet-500' : 'bg-gray-100 border-gray-400 opacity-75'"
                    class="rounded-2xl shadow-md p-5 border-t-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        Algorithms
                    </h2>

                    <div class="space-y-1 text-gray-600">
                        <p>📍 Aula A12</p>
                        <p>👨‍🏫 Docente: Juan Lopez</p>
                        <p>📅 Días: Lunes y Miércoles</p>
                        <p>🕒 Horario: 08:00 - 10:00</p>
                        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                Estado
                            </span>
                            <div class="flex items-center gap-3">

                                <span x-text="active ? 'Activa' : 'Inactiva'"
                                    :class="active ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                </span>

                                <label class="relative inline-flex items-center cursor-pointer">

                                    <input type="checkbox" x-model="active" class="sr-only peer">

                                    <div
                                        class="w-11 h-6 bg-gray-300 rounded-full
                                                peer-checked:bg-violet-500
                                                transition-colors duration-300
                                                after:content-['']
                                                after:absolute
                                                after:top-[2px]
                                                after:left-[2px]
                                                after:bg-white
                                                after:border
                                                after:rounded-full
                                                after:h-5
                                                after:w-5
                                                after:transition-all
                                                peer-checked:after:translate-x-full">
                                    </div>

                                </label>

                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ active: false }"
                    :class="active ? 'bg-white border-violet-500' : 'bg-gray-100 border-gray-400 opacity-75'"
                    class="rounded-2xl shadow-md p-5 border-t-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        Database Systems
                    </h2>

                    <div class="space-y-1 text-gray-600">
                        <p>📍 Aula B05</p>
                        <p>👨‍🏫 Docente: Sara Juarez</p>
                        <p>📅 Días: Martes y Jueves</p>
                        <p>🕒 Horario: 10:00 - 12:00</p>

                        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">

                            <span class="text-sm text-gray-500">
                                Estado
                            </span>

                            <div class="flex items-center gap-3">

                                <span x-text="active ? 'Activa' : 'Inactiva'"
                                    :class="active ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                </span>

                                <label class="relative inline-flex items-center cursor-pointer">

                                    <input type="checkbox" x-model="active" class="sr-only peer">

                                    <div
                                        class="w-11 h-6 bg-gray-300 rounded-full
                                                peer-checked:bg-violet-500
                                                transition-colors duration-300
                                                after:content-['']
                                                after:absolute
                                                after:top-[2px]
                                                after:left-[2px]
                                                after:bg-white
                                                after:border
                                                after:rounded-full
                                                after:h-5
                                                after:w-5
                                                after:transition-all
                                                peer-checked:after:translate-x-full">
                                    </div>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

                <div x-data="{ active: true }"
                    :class="active ? 'bg-white border-violet-500' : 'bg-gray-100 border-gray-400 opacity-75'"
                    class="rounded-2xl shadow-md p-5 border-t-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        Web Development
                    </h2>

                    <div class="space-y-1 text-gray-600">
                        <p>📍 Aula C21</p>
                        <p>👨‍🏫 Docente: Mariano Sanchez</p>
                        <p>📅 Días: Lunes y Miércoles</p>
                        <p>🕒 Horario: 08:00 - 10:00</p>

                        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">

                            <span class="text-sm text-gray-500">
                                Estado
                            </span>

                            <div class="flex items-center gap-3">

                                <span x-text="active ? 'Activa' : 'Inactiva'"
                                    :class="active ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                </span>

                                <label class="relative inline-flex items-center cursor-pointer">

                                    <input type="checkbox" x-model="active" class="sr-only peer">

                                    <div
                                        class="w-11 h-6 bg-gray-300 rounded-full
                                                peer-checked:bg-violet-500
                                                transition-colors duration-300
                                                after:content-['']
                                                after:absolute
                                                after:top-[2px]
                                                after:left-[2px]
                                                after:bg-white
                                                after:border
                                                after:rounded-full
                                                after:h-5
                                                after:w-5
                                                after:transition-all
                                                peer-checked:after:translate-x-full">
                                    </div>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>
