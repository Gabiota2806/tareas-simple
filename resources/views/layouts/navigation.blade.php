<!-- Corrección: En móvil no es fija (la controla el contenedor padre), en PC se clava al costado izquierdo -->
<!-- Corrección de Ancho: En celulares es siempre 'w-64' para que se lean las letras; en PC cambia dinámicamente -->
<aside :class="sidebarExpanded ? 'lg:w-64' : 'lg:w-20'" 
       class="flex flex-col h-full w-64 bg-white border-r border-gray-100 transition-all duration-300 lg:fixed lg:left-0 lg:top-0 lg:h-screen lg:z-40 shadow-xl lg:shadow-none">
    
    <!-- En celular permite scroll vertical autónomo, en PC se bloquea el desborde -->
    <div class="flex h-full flex-col px-4 py-4 overflow-y-auto lg:overflow-hidden">
        
        <!-- HEADER / LOGO (Corregido: visible en móvil 'flex', y el texto responde dinámicamente en PC) -->
        <div class="flex items-center justify-between mb-6 lg:mb-8 pt-2 lg:pt-0 transition-all duration-300 flex-row">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 overflow-hidden">
                <img src="{{ asset('images/logo_unitask.jpeg') }}" alt="UniTask Logo" 
                     class="rounded-xl shadow-sm object-cover shrink-0 transition-all duration-300"
                     :class="sidebarExpanded ? 'w-10 h-10' : 'w-7 h-7'">
                <!-- Cambiado: En celular siempre visible, en PC depende de sidebarExpanded -->
                <span :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" x-transition.opacity.duration.300ms class="block text-2xl font-bold text-violeta-moderno whitespace-nowrap tracking-tight ml-1">
                    UniTask
                </span>
            </a>
            
            <!-- El botón de contraer menú solo sirve en computadoras, lo ocultamos en móvil con 'hidden lg:block' -->
            <button @click="sidebarExpanded = !sidebarExpanded" 
                    class="hidden lg:block text-gray-400 hover:text-violeta-moderno transition p-1 rounded-xl hover:bg-violet-50 shrink-0 border border-transparent hover:border-violet-100" 
                    :title="sidebarExpanded ? 'Contraer menú' : 'Expandir menú'">
                <svg x-show="sidebarExpanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                <svg x-show="!sidebarExpanded" style="display:none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
            </button>
        </div>

                      <!-- Menú de Enlaces (Modificado flex-1 por lg:flex-1 para que no empuje el tip en móviles) -->
        <nav class="space-y-2 lg:flex-1 mt-2 lg:mt-4">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->routeIs('dashboard') || request()->routeIs('subjects.show') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Inicio' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <!-- Modificado: En móvil es siempre block, en PC se oculta si la barra se contrae -->
                <span :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" class="block whitespace-nowrap">Inicio</span>
            </a>

            <a href="{{ url('/careers') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->is('careers*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Carreras' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm0 0v7" /></svg>
                <span :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" class="block whitespace-nowrap">Carreras</span>
            </a>

            <a href="{{ url('/subjects') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->routeIs('subjects.index') || request()->routeIs('subjects.create') || request()->routeIs('subjects.edit') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Materias' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" class="block whitespace-nowrap">Materias</span>
            </a>

            <a href="{{ route('academic-record.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->routeIs('academic-record.*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Libreta' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" class="block whitespace-nowrap">Libreta</span>
            </a>

                        <a href="{{ url('/calendar') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->is('calendar*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Calendario' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <!-- Corrección: Forzamos visibilidad en móvil e inversión de lógica en PC -->
                <span :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" class="block whitespace-nowrap">Calendario</span>
            </a>

            <a href="{{ route('historial.index') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                {{ request()->routeIs('historial.*') ? 'bg-violet-100 text-violeta-moderno' : 'text-gray-600 hover:bg-gray-50 hover:text-violeta-moderno' }}"
                :title="!sidebarExpanded ? 'Historial' : ''">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="whitespace-nowrap">Historial</span>
            </a>
        </nav>

                <!-- Bloque Pro Tip optimizado para móviles (Modificado mt-auto por mt-6 mb-6 para subirlo) -->
        <div class="mt-6 lg:mt-auto mb-6 lg:mb-0 transition-all duration-300 pt-4 border-t border-gray-100 lg:border-t-0" x-data="{
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
            
            <!-- Mensaje expandido (Corrección: visible siempre en móvil, responde al sidebar en PC) -->
            <div :class="sidebarExpanded ? 'lg:block' : 'lg:hidden'" class="block rounded-2xl bg-gradient-to-br from-violet-50 to-purple-50 p-4 border border-violet-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-violet-200 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" /></svg>
                </div>
                
                <div class="relative z-10 flex items-start gap-3">
                    <span class="text-2xl leading-none">💡</span>
                    <div>
                        <p class="font-bold text-violet-900 text-sm">Pro Tip</p>
                        <p class="mt-1 text-xs text-violet-700/90 leading-relaxed font-medium" x-text="currentTip"></p>
                    </div>
                </div>
            </div>
            
            <!-- Icono colapsado (Oculto totalmente en móviles, solo para PC colapsado) -->
            <div :class="sidebarExpanded ? 'lg:hidden' : 'lg:flex'" class="hidden justify-center transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100 flex items-center justify-center cursor-help shadow-sm hover:scale-105 transition-transform" :title="'Pro Tip: ' + currentTip">
                    <span class="text-lg leading-none">💡</span>
                </div>
            </div>
        </div>
        
    </div>
</aside>
