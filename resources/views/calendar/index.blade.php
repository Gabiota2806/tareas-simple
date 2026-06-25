<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

        <!-- Encabezado (Ajustado el tamaño de texto para celulares) -->
        <div class="mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                Calendario Académico
            </h2>

            <p class="mt-1 text-xs sm:text-sm text-gray-500">
                Visualizá entregas, parciales, finales y actividades académicas.
            </p>
        </div>

        <!-- Controles superiores -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
            
            <!-- Selector de Carrera -->
            <form method="GET" action="{{ route('calendar.index') }}" class="w-full md:w-64">
                <div x-data="{ open: false }" class="relative w-full">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300">
                        <span class="truncate flex-1 text-left">
                            @if(isset($selectedCareer) && $selectedCareer)
                                {{ $careers->firstWhere('id', $selectedCareer)->name ?? 'Seleccione una carrera' }}
                            @else
                                Seleccione una carrera
                            @endif
                        </span>
                        <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-full max-h-60 overflow-y-auto rounded-xl border border-gray-100 bg-white p-2 shadow-xl" style="display:none;">
                        @if(isset($careers) && $careers->isNotEmpty())
                            @foreach($careers as $career)
                                <button type="submit" name="career_id" value="{{ $career->id }}" class="w-full truncate rounded-lg px-3 py-2 text-left text-sm hover:bg-violet-50 transition {{ (isset($selectedCareer) && $selectedCareer == $career->id) ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700' }}">{{ $career->name }}</button>
                            @endforeach
                        @else
                            <div class="px-3 py-2 text-sm text-gray-500 italic">No hay carreras</div>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Toggle de Modo -->
            <div class="flex bg-gray-200 p-1 rounded-xl shadow-inner font-nunito" id="calendar-mode-toggle">
                <button type="button" data-mode="deliverables" class="mode-btn px-5 py-2 text-sm font-bold rounded-lg transition bg-white shadow text-violet-700">
                    ✅ Entregas y Exámenes
                </button>
                <button type="button" data-mode="schedules" class="mode-btn px-5 py-2 text-sm font-bold rounded-lg transition text-gray-500 hover:text-gray-700">
                    🏫 Horarios de Cursada
                </button>
            </div>
        </div>

        <div class="rounded-2xl sm:rounded-3xl bg-white p-3 sm:p-5 shadow-sm border border-gray-100">
            <div id="calendar" class="w-full"></div>
        </div>
    </div>
<style>
        .fc {
            font-family: inherit;
        }

        /* Título del Mes: Grande en PC, sumamente compacto en móvil para que quepa en una sola línea */
        .fc .fc-toolbar-title {
            font-size: 0.95rem !important;
            font-weight: 800 !important;
            color: #1f2937 !important;
            text-transform: capitalize !important;
            white-space: nowrap !important;
        }
        @media (min-width: 640px) {
            .fc .fc-toolbar-title {
                font-size: 1.4rem !important;
            }
        }

        /* Botones superiores: Proporciones adaptables para celular y PC */
        .fc .fc-button {
            background: #7C3AED !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 0.4rem 0.6rem !important;
            font-weight: 700 !important;
            box-shadow: none !important;
            font-size: 0.75rem !important;
        }
        @media (min-width: 640px) {
            .fc .fc-button {
                border-radius: 15px !important;
                padding: 0.7rem 1rem !important;
                font-size: 0.9rem !important;
            }
        }

        .fc .fc-button:hover {
            background: #6D28D9 !important;
        }

        .fc .fc-button-primary:disabled {
            background: #EDE9FE !important;
            color: #7C3AED !important;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background: #F5F3FF !important;
            box-shadow: inset 0 0 0 2px #C4B5FD !important; /* Border sutil interno */
        }
        
        .fc th.fc-day-today .fc-col-header-cell-cushion {
            background-color: #7C3AED;
            color: white !important;
            border-radius: 8px;
            padding: 4px 12px;
            margin-top: 4px;
            margin-bottom: 4px;
            display: inline-block;
            font-weight: 800;
            box-shadow: 0 2px 4px rgba(124, 58, 237, 0.3);
        }

        /* Cabecera de Días (Lunes, Martes...): Compacta */
        .fc .fc-col-header-cell {
            background: #F8FAFC;
            padding: 6px 0;
            font-size: 0.7rem;
            color: #64748B;
            text-transform: uppercase;
        }
        @media (min-width: 640px) {
            .fc .fc-col-header-cell {
                padding: 10px 0;
                font-size: 0.8rem;
                color: #64748B;
            }
        }

        .fc .fc-daygrid-day-number {
            color: #334155;
            font-size: 0.85rem;
            padding: 8px;
        }
        
        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            background-color: #7C3AED;
            color: white !important;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
            font-weight: 800;
            box-shadow: 0 2px 4px rgba(124, 58, 237, 0.3);
            padding: 0;
        }

        /* Tarjeta de Eventos Académicos */
        .fc-theme-standard td,
        .fc-theme-standard th,
        .fc-theme-standard .fc-scrollgrid {
            border-color: #E5E7EB;
        }

        .fc-event {
            border: none !important;
            border-radius: 10px !important;
            padding: 3px 6px !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
        }

        /* BARRA SUPERIOR INDESTRUCTIBLE: Fuerza una sola fila rígida y evita desdoblamientos o clones */
        .fc .fc-toolbar {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 4px !important;
            width: 100% !important;
        }

        .fc .fc-toolbar-chunk {
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            flex-wrap: nowrap !important; /* Prohibido romperse hacia abajo en celulares */
        }

        .fc .fc-button-group {
            gap: 3px !important;
        }

        .fc .fc-button-group .fc-button {
            margin: 0 !important;
        }

                /* ESCUDO DEFINITIVO ANTI-DUPLICADO DE FECHAS EN MÓVIL */
        .fc-toolbar-title {
            display: inline-flex !important;
            gap: 4px;
        }
        /* Si FullCalendar renderiza dos fechas en espejo (rango duplicado), esconde la segunda por la fuerza */
        .fc-toolbar-title span + span, 
        .fc-toolbar-title text + text {
            display: none !important;
        }
    </style>

 <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const calendarEl = document.getElementById('calendar');
            const upcomingEventsEl = document.getElementById('upcoming-events');
            const eventsCountEl = document.getElementById('events-count');
            const upcomingCountEl = document.getElementById('upcoming-count');

            let calendarEvents = [];

            try {
                const response = await fetch('/calendar/events', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                calendarEvents = await response.json();
            } catch (error) {
                console.error('Error al cargar eventos del calendario:', error);
            }

            // Validar que todos tengan extendedProps.type
            calendarEvents = calendarEvents.map(ev => {
                if (!ev.extendedProps) ev.extendedProps = {};
                if (!ev.extendedProps.type) ev.extendedProps.type = 'task'; // fallback
                return ev;
            });

            let currentEvents = calendarEvents;

            // DETECCIÓN INTELIGENTE DE MÓVIL (Menor a 768px de ancho)
            const esMovil = window.innerWidth < 768;

            // ESCUDO DE LIMPIEZA ABSOLUTA: Destruye el calendario viejo en memoria para evitar duplicados
            if (window.miCalendarioInstancia) {
                window.miCalendarioInstancia.destroy();
            }

            const calendar = new window.FullCalendar.Calendar(calendarEl, {
                plugins: [
                    window.FullCalendar.dayGridPlugin,
                    window.FullCalendar.interactionPlugin,
                    window.FullCalendar.listPlugin
                ],

                // Si es móvil arranca en Agenda Semanal, si es PC arranca en Vista de Mes
                initialView: esMovil ? 'listWeek' : 'dayGridMonth',
                locale: 'es',
                handleWindowResize: true,

                // Control de altura estricto para celulares
                height: esMovil ? 380 : 'auto',

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    list: 'Agenda'
                },

                height: esMovil ? 380 : 'auto',
                contentHeight: 650,
                
                // Formato de hora en 24hs
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                displayEventEnd: true,
                eventDisplay: 'block', // Fuerza a que todos los eventos tengan fondo de color (no solo puntitos)
                
                // Muestra el rango de días de la semana
                views: {
                    listWeek: {
                        titleFormat: { day: 'numeric', month: 'short', year: 'numeric' }
                    },
                    dayGridMonth: {
                        titleFormat: { year: 'numeric', month: 'long' }
                    }
                },

                // BARRA SUPERIOR DINÁMICA: Compacta en móvil para evitar encimamientos, extendida en PC
                headerToolbar: esMovil ? {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today' // En móviles ocultamos el cambio de vistas para cuidar el espacio
                } : {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,listWeek'
                },

                // Renderizado personalizado de la tarjeta del evento
                eventContent: function(arg) {
                    let timeHtml = '';
                    // Si el evento tiene hora (no es de todo el día), mostramos la franja
                    if (arg.timeText) {
                        timeHtml = `<div class="font-bold text-[10px] bg-black/20 rounded px-1.5 py-0.5 mb-1 inline-block">${arg.timeText}</div>`;
                    }
                    
                    return {
                        html: `
                        <div class="p-1 w-full overflow-hidden leading-tight">
                            ${timeHtml}
                            <div class="font-semibold text-xs whitespace-normal">${arg.event.title}</div>
                        </div>
                        `
                    };
                },

                events: function(info, successCallback, failureCallback) {
                    successCallback(currentEvents);
                },

                eventClick: function(info) {
                    const modal = document.getElementById('task-modal');
                    const subtasksContainer = document.getElementById('modal-subtasks');

                    document.getElementById('modal-title').textContent = info.event.title;

                    document.getElementById('modal-description').textContent =
                        info.event.extendedProps.description ?? 'Sin descripción';

                    const subtasks = info.event.extendedProps.subtasks ?? [];

                    if (subtasks.length > 0) {
                        subtasksContainer.innerHTML = subtasks.map((subtask, index) => {
                            const symbol = index === subtasks.length - 1 ? '└─' : '├─';

                            return `
                            <div class="py-1">
                                <span class="text-gray-400">${symbol}</span>
                                <span>${subtask}</span>
                            </div>
                        `;
                        }).join('');
                    } else {
                        subtasksContainer.innerHTML = 'Sin subtareas';
                    }

                    modal.classList.remove('hidden');
                }
            });

                       // Guardamos la instancia de renderizado limpia
            calendar.render();
            window.miCalendarioInstancia = calendar; 

            // INTERCAMBIO DINÁMICO DE VISTAS (Celular <--> PC en tiempo real)
            const detectorPantalla = window.matchMedia('(max-width: 767px)');
            
            function ajustarVistaCalendario(e) {
                if (e.matches) {
                    calendar.changeView('listWeek');
                    calendar.setOption('height', 380);
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today'
                    });
                } else {
                    calendar.changeView('dayGridMonth');
                    calendar.setOption('height', 'auto');
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,listWeek' // Modificado timeGridWeek por dayGridWeek
                    });
                }
                calendar.updateSize();
            }
            detectorPantalla.addEventListener('change', ajustarVistaCalendario);

            // Filtrado interactivo por Modo
            const modeBtns = document.querySelectorAll('.mode-btn');
            let currentMode = 'deliverables'; // 'deliverables' | 'schedules'
            
            function updateMode(mode) {
                currentMode = mode;
                
                // Actualizar UI de botones
                modeBtns.forEach(btn => {
                    if (btn.dataset.mode === mode) {
                        btn.classList.add('bg-white', 'shadow', 'text-violet-700');
                        btn.classList.remove('text-gray-500', 'hover:text-gray-700');
                    } else {
                        btn.classList.remove('bg-white', 'shadow', 'text-violet-700');
                        btn.classList.add('text-gray-500', 'hover:text-gray-700');
                    }
                });
                
                // Filtrar eventos
                if (mode === 'deliverables') {
                    currentEvents = calendarEvents.filter(ev => ['task', 'exam'].includes(ev.extendedProps.type));
                    // Cambiar a vista mes por defecto si estábamos en semana
                    if (calendar.view.type === 'dayGridWeek' && mode === 'deliverables') {
                        calendar.changeView('dayGridMonth');
                    }
                } else if (mode === 'schedules') {
                    currentEvents = calendarEvents.filter(ev => ev.extendedProps.type === 'class');
                    // Cambiar a vista semana en bloque (dayGridWeek en lugar de timeGridWeek)
                    calendar.changeView('dayGridWeek');
                }
                
                calendar.refetchEvents();
            }
            
            modeBtns.forEach(btn => {
                btn.addEventListener('click', () => updateMode(btn.dataset.mode));
            });
            
            // Ejecutar modo inicial
            updateMode('deliverables');

            document.getElementById('close-modal').addEventListener('click', () => {
                document.getElementById('task-modal').classList.add('hidden');
            });

            document.getElementById('task-modal').addEventListener('click', (event) => {
                if (event.target.id === 'task-modal') {
                    document.getElementById('task-modal').classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Ventana emergente (Modal) optimizada para celulares y computadoras -->
    <!-- Envoltura gris traslúcida con padding de protección para que no se pegue al cristal en móviles -->
    <div id="task-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

        <!-- Caja blanca: Añadida altura máxima responsiva (max-h) y scroll vertical interno automático -->
        <div class="w-full max-w-lg rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-xl flex flex-col max-h-[90vh] overflow-y-auto">

            <!-- Encabezado del Modal -->
            <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-2">
                <h2 id="modal-title" class="text-lg sm:text-xl font-bold text-gray-800 truncate pr-4">
                    Tarea
                </h2>

                <button id="close-modal" class="text-gray-400 hover:text-gray-700 text-xl p-2 hover:bg-gray-50 rounded-xl transition">
                    ✕
                </button>
            </div>

            <!-- Cuerpo del Modal (Sección de contenidos con espacio fluido) -->
            <div class="space-y-4 flex-1">

                <div>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Descripción
                    </p>

                    <p id="modal-description" class="text-sm sm:text-base text-gray-700 mt-1 leading-relaxed break-words">
                        Sin descripción
                    </p>
                </div>

                <div>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Subtareas
                    </p>

                    <div id="modal-subtasks" class="rounded-xl sm:rounded-2xl bg-gray-50 p-3 sm:p-4 text-xs sm:text-sm text-gray-600 border border-gray-100/50 space-y-1">
                        Sin subtareas
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
