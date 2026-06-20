<aside :class="sidebarExpanded ? 'w-64' : 'w-20'" class="fixed left-0 top-0 z-40 hidden h-screen border-r border-gray-100 bg-white lg:flex flex-col transition-all duration-300">
    <div class="flex h-full flex-col px-4 py-6 overflow-hidden">
        
        <!-- HEADER / LOGO -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="text-2xl font-bold text-violeta-moderno whitespace-nowrap">
                    UniTask
                </span>
            </a>
            
            <button @click="sidebarExpanded = !sidebarExpanded" class="text-gray-400 hover:text-violeta-moderno transition p-1.5 rounded-lg hover:bg-violet-50 shrink-0" :title="sidebarExpanded ? 'Contraer menú' : 'Expandir menú'">
                <svg x-show="sidebarExpanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                <svg x-show="!sidebarExpanded" style="display:none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
            </button>
        </div>

        <nav class="space-y-2 flex-1 mt-4">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->routeIs('dashboard') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Inicio' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="whitespace-nowrap">Inicio</span>
            </a>

            <a href="{{ url('/careers') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->is('careers*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Carreras' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm0 0v7" /></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="whitespace-nowrap">Carreras</span>
            </a>

            <a href="{{ url('/subjects') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->is('subjects*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Materias' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="whitespace-nowrap">Materias</span>
            </a>

            <a href="{{ url('/calendar') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->is('calendar*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Calendario' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="whitespace-nowrap">Calendario</span>
            </a>
        </nav>

        <div class="mt-auto transition-all duration-300" x-data="{
            tips: [
                'Divide las tareas grandes en pequeñas.',
                'Archiva las materias que ya finalizaste.',
                'Asigna fechas límite para mantener el ritmo.',
                'Prioriza las tareas usando las etiquetas.',
                'Revisa el calendario para planear tu semana.',
                'Usa el buscador superior para encontrar tareas.',
                'Mueve las tareas al estado correcto a tiempo.'
            ],
            currentTip: ''
        }" x-init="currentTip = tips[Math.floor(Math.random() * tips.length)]">
            <!-- Mensaje expandido -->
            <div x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="rounded-2xl bg-gradient-to-br from-violet-50 to-purple-50 p-4 border border-violet-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-violet-200 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" /></svg>
                </div>
                
                <div class="relative z-10 flex items-start gap-3">
                    <span class="text-2xl leading-none">💡</span>
                    <div>
                        <p class="font-bold text-violet-900 text-sm">Pro Tip</p>
                        <p class="mt-1 text-xs text-violet-700/90 leading-relaxed font-medium" x-text="currentTip">
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Icono colapsado -->
            <div x-show="!sidebarExpanded" style="display:none;" class="flex justify-center transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100 flex items-center justify-center cursor-help shadow-sm hover:scale-105 transition-transform" :title="'Pro Tip: ' + currentTip">
                    <span class="text-lg leading-none">💡</span>
                </div>
            </div>
        </div>
        
    </div>
</aside>
