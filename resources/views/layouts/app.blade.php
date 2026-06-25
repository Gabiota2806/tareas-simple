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
    @php
        $userUniversities = \App\Models\University::where('user_id', Auth::id())->orderBy('name')->get();
        $activeUniId = session('active_university_id');
        
        if (!$activeUniId && $userUniversities->isNotEmpty()) {
            $activeUniId = $userUniversities->first()->id;
            session(['active_university_id' => $activeUniId]);
        }

        $activeUni = $userUniversities->firstWhere('id', $activeUniId);
        $activeUniName = $activeUni ? addslashes($activeUni->acronym ?: $activeUni->name) : 'Sin Universidad';
    @endphp

        <!-- Contenedor general reactivo con Alpine.js (Corregido con la clase 'flex') -->
    <div x-data="{
        userMenuOpen: false,
        universityOpen: false,
        mobileSidebarOpen: false,
        sidebarExpanded: localStorage.getItem('sidebarExpanded') !== 'false',
        init() {
            this.$watch('sidebarExpanded', val => localStorage.setItem('sidebarExpanded', val));
        }
    }" class="min-h-screen flex">

        <!-- Fondo oscuro translúcido para Celular (Z-INDEX: 40) -->
        <div x-show="mobileSidebarOpen" 
             @click="mobileSidebarOpen = false" 
             x-transition.opacity
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden" 
             style="display: none;"></div>

        <!-- CONTENEDOR DE NAVEGACIÓN HÍBRIDO (ÚNICO) -->
        <div :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
             class="fixed inset-y-0 left-0 z-50 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shrink-0">
            @include('layouts.navigation')
        </div>

        <!-- MAIN WRAPPER (Restaurado): Empuja el contenido a la derecha en PC según el estado del menú y evita desajustes -->
        <div class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300" 
             :class="sidebarExpanded ? 'lg:pl-64' : 'lg:pl-20'">

            <!-- Top bar (Aquí arranca directamente el Header) -->
            <header class="sticky top-0 z-30 border-b border-gray-100 bg-white/90 backdrop-blur-md">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6">

                    <!-- Botón Hamburguesa (SOLO visible en móvil para desplegar el Sidebar) -->
                    <div class="flex items-center gap-2 lg:hidden">
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="rounded-xl p-2 text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                                       <!-- Título dinámico de sección (Corregido: oculto en móvil 'hidden', visible en PC 'md:block') -->
                    <div class="hidden md:block lg:flex-none">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-base sm:text-lg font-semibold text-gray-800 truncate">
                                @if (request()->routeIs('dashboard'))
                                    Inicio
                                @elseif(request()->routeIs('subjects.show'))
                                    Tareas
                                @elseif(request()->is('subjects*'))
                                    Materias
                                @elseif(request()->is('universities*'))
                                    Universidad
                                @elseif(request()->is('careers*'))
                                    Carreras
                                @elseif(request()->is('calendar*'))
                                    Calendario
                                @elseif(request()->routeIs('academic-record.*'))
                                    Libreta Universitaria
                                @elseif(request()->routeIs('tasks.create'))
                                    Nueva tarea
                                @else
                                    UniTask
                                @endif
                            </h1>
                        @endisset
                    </div>
                              <!-- Buscador (Oculto en móviles pequeños, se despliega a partir de tablets 'md:') -->
                    <div class="hidden md:block flex-1 max-w-xl mx-4">
                        <form action="{{ route('search.index') }}" method="GET" class="relative">
                            <button type="submit" class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 hover:text-violeta-moderno">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>

                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar tareas o materias..."
                                class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-violeta-moderno focus:bg-white focus:ring-1 focus:ring-violeta-moderno">
                        </form>
                    </div>

                    <!-- Right Controls (Universidad + Usuario) -> Escalable en móvil y PC -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        
                        <!-- Universidad -->
                        <div class="relative w-[130px] sm:w-[180px]">
                            <span class="absolute -top-2 left-4 bg-white px-1 text-[9px] sm:text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                                Universidad
                            </span>

  <button @click="universityOpen = !universityOpen"
                                class="flex w-full items-center justify-between gap-1 sm:gap-2 rounded-2xl border border-violet-100 bg-white px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno">

                                <span class="truncate flex-1 text-left">{{ $activeUniName }}</span>

                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="universityOpen" @click.away="universityOpen = false" x-transition
                                class="absolute z-50 mt-2 w-48 sm:w-56 rounded-2xl border border-gray-100 bg-white p-2 shadow-xl right-0"
                                style="display:none;">

                                <!-- Formulario del Selector de Universidades (Restaurado y sanado) -->
                                <form method="POST" action="{{ route('active-university.set') }}">
                                    @csrf
                                    @forelse($userUniversities as $uni)
                                        @php $uniDisplayName = $uni->acronym ?: $uni->name; @endphp
                                        <button type="submit" name="university_id" value="{{ $uni->id }}"
                                            title="{{ $uni->name }}"
                                            class="w-full truncate rounded-xl px-3 py-2 text-left text-sm transition {{ $uni->id == $activeUniId ? 'bg-violet-50 text-violeta-moderno font-bold' : 'hover:bg-violet-50 text-gray-700' }}">
                                            {{ $uniDisplayName }}
                                        </button>
                                    @empty
                                        <div class="px-3 py-2 text-sm text-gray-500">Sin universidades</div>
                                    @endforelse
                                </form>

                                <div class="border-t border-gray-100 mt-2 pt-2">
                                    <a href="{{ route('universities.index') }}" class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-left text-sm text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Administrar Universidades
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Usuario -->
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-2 sm:gap-3 rounded-2xl border border-gray-100 bg-white px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                                <span
                                    class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-violet-100 text-xs sm:text-sm font-bold text-violeta-moderno">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>

                                <span class="hidden sm:block">
                                    {{ Auth::user()->name }}
                                </span>

                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-transition
                                class="absolute right-0 z-50 mt-3 w-48 sm:w-56 rounded-2xl border border-gray-100 bg-white p-2 shadow-xl"
                                style="display: none;">
                                <div class="border-b border-gray-100 px-4 py-3">
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
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
                        <!-- End of Right Controls -->
                    </div>
                </div>

                <!-- Buscador inferior móvil -->
                <div class="border-t border-gray-100 px-4 py-3 md:hidden bg-white">
                    <form action="{{ route('search.index') }}" method="GET" class="relative">
                        <button type="submit" class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar tareas o materias..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2 pl-11 pr-4 text-sm text-gray-700 outline-none focus:border-violeta-moderno focus:ring-1 focus:ring-violeta-moderno">
                    </form>
                </div>
            </header>

                      <!-- Page content (Corregido: Fluye de forma natural y el fondo gris cubre el 100% de la pantalla) -->
            <main class="p-4 sm:p-6 flex-1 bg-gray-100 min-h-[calc(100vh-80px)] w-full">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
