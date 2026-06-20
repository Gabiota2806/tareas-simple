<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>UniTask</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div x-data="{
        userMenuOpen: false,
        universityOpen: false,
        careerOpen: false,
        selectedUniversity: 'UTN',
        selectedCareer: 'TUP'
    }" class="min-h-screen">

        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 z-40 hidden h-screen w-64 border-r border-gray-100 bg-white lg:block">
            <div class="flex h-full flex-col px-5 py-6">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-violeta-moderno">
                    UniTask
                </a>

                <nav class="mt-10 space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="block rounded-xl px-4 py-3 text-sm font-medium transition
                        {{ request()->routeIs('dashboard') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}">
                        Inicio
                    </a>

                    <a href="{{ url('/universities') }}"
                        class="block rounded-xl px-4 py-3 text-sm font-medium transition
                        {{ request()->is('universities*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}">
                        Universidad
                    </a>

                    <a href="{{ url('/careers') }}"
                        class="block rounded-xl px-4 py-3 text-sm font-medium transition
                        {{ request()->is('careers*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}">
                        Carreras
                    </a>

                    <a href="{{ url('/subjects') }}"
                        class="block rounded-xl px-4 py-3 text-sm font-medium transition
                        {{ request()->is('subjects*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}">
                        Materias
                    </a>

                    <a href="{{ url('/calendar') }}"
                        class="block rounded-xl px-4 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50 hover:text-violeta-moderno">
                        Calendario
                    </a>
                </nav>

                <div class="mt-auto rounded-2xl bg-violet-50 p-4 text-sm text-violeta-moderno">
                    <p class="font-semibold">Mantené el foco</p>
                    <p class="mt-1 text-xs text-gray-500">
                        Organizá tu cursada paso a paso.
                    </p>
                </div>
            </div>
        </aside>

        <!-- Main wrapper -->
        <div class="lg:ml-64">

            <!-- Top bar -->
            <header class="sticky top-0 z-30 border-b border-gray-100 bg-white/90 backdrop-blur-md">
                <div class="flex h-20 items-center justify-between gap-4 px-6">

                    <!-- Título dinámico de sección -->
                    <div>
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-lg font-semibold text-gray-800">
                                @if (request()->routeIs('dashboard'))
                                    Inicio
                                @elseif(request()->is('subjects*'))
                                    Materias
                                @elseif(request()->is('universities*'))
                                    Universidad
                                @elseif(request()->is('careers*'))
                                    Carreras
                                @elseif(request()->is('calendar*'))
                                    Calendario
                                @elseif(request()->routeIs('tasks.create'))
                                    Nueva tarea
                                @else
                                    UniTask
                                @endif
                            </h1>
                        @endisset
                    </div>

                    <!-- Buscador y filtros académicos -->
                    <div class="hidden md:grid flex-1 max-w-4xl grid-cols-[1fr_140px_160px] items-center gap-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>

                            <input type="text" placeholder="Buscar tareas, materias o fechas..."
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:bg-white focus:ring-1 focus:ring-violeta-moderno">
                        </div>

                        <div class="relative w-[140px]">
                            <span
                                class="absolute -top-2 left-4 bg-white px-1 text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                                Universidad
                            </span>

                            <button @click="universityOpen = !universityOpen"
                                class="flex w-full items-center justify-between rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300">

                                <span x-text="selectedUniversity"></span>

                                <svg class="h-4 w-4 text-violeta-moderno" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="universityOpen" @click.away="universityOpen = false" x-transition
                                class="absolute z-50 mt-2 w-full rounded-2xl border border-gray-100 bg-white p-2 shadow-xl"
                                style="display:none;">

                                <button @click="selectedUniversity='UTN'; universityOpen=false"
                                    class="w-full rounded-xl px-3 py-2 text-left text-sm hover:bg-violet-50">
                                    UTN
                                </button>

                                <button @click="selectedUniversity='UNaF'; universityOpen=false"
                                    class="w-full rounded-xl px-3 py-2 text-left text-sm hover:bg-violet-50">
                                    UNaF
                                </button>
                            </div>
                        </div>

                        <div class="relative w-[160px]">
                            <span
                                class="absolute -top-2 left-4 bg-white px-1 text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                                Carrera
                            </span>

                            <button @click="careerOpen = !careerOpen"
                                class="flex w-full items-center justify-between rounded-2xl border border-violet-100 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300">

                                <span x-text="selectedCareer"></span>

                                <svg class="h-4 w-4 text-violeta-moderno" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="careerOpen" @click.away="careerOpen = false" x-transition
                                class="absolute z-50 mt-2 w-full rounded-2xl border border-gray-100 bg-white p-2 shadow-xl"
                                style="display:none;">

                                <button @click="selectedCareer='TUP'; careerOpen=false"
                                    class="w-full rounded-xl px-3 py-2 text-left text-sm hover:bg-violet-50">
                                    TUP
                                </button>

                                <button @click="selectedCareer='Ing. Sistemas'; careerOpen=false"
                                    class="w-full rounded-xl px-3 py-2 text-left text-sm hover:bg-violet-50">
                                    Ing. Sistemas
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-100 font-bold text-violeta-moderno">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>

                            <span class="hidden sm:block">
                                {{ Auth::user()->name }}
                            </span>

                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-transition
                            class="absolute right-0 z-50 mt-3 w-56 rounded-2xl border border-gray-100 bg-white p-2 shadow-xl"
                            style="display: none;">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                                class="block rounded-xl px-4 py-3 text-sm text-gray-600 transition hover:bg-gray-50 hover:text-violeta-moderno">
                                Perfil
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                    class="w-full rounded-xl px-4 py-3 text-left text-sm text-red-500 transition hover:bg-red-50">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Buscador y filtros móvil -->
                <div class="space-y-3 border-t border-gray-100 px-6 py-3 md:hidden">
                    <input type="text" placeholder="Buscar..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-violeta-moderno focus:ring-1 focus:ring-violeta-moderno">

                    <select
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-violeta-moderno focus:ring-1 focus:ring-violeta-moderno">
                        <option>UTN</option>
                        <option>UNaF</option>
                    </select>

                    <select
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-violeta-moderno focus:ring-1 focus:ring-violeta-moderno">
                        <option>TUP</option>
                        <option>Ing. Sistemas</option>
                    </select>
                </div>
            </header>

            <!-- Page content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
