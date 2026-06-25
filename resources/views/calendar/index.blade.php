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

        <div class="grid gap-6 grid-cols-1 lg:grid-cols-[1fr_320px]">

                       <!-- Contenedor del Calendario corregido: entra al 100% en celulares sin scroll lateral -->
            <div class="rounded-2xl sm:rounded-3xl bg-white p-3 sm:p-5 shadow-sm border border-gray-100">
                <!-- Quitamos el min-w-[600px] para que se adapte de forma nativa a los bordes de la pantalla -->
                <div id="calendar" class="w-full"></div>
            </div>

            <!-- Panel lateral (Se acomodará abajo en celular y al costado en pantallas grandes) -->
            <div class="space-y-4">

                <div class="rounded-2xl sm:rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-3 text-sm sm:text-base">
                        Próximas entregas
                    </h3>

                    <div id="upcoming-events" class="space-y-3">
                        <p class="text-sm text-gray-400">
                            No hay entregas próximas cargadas.
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl sm:rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-3 text-sm sm:text-base">
                        Resumen
                    </h3>

                    <div class="space-y-2 text-sm text-gray-600">
                        <p>
                            📚 Eventos cargados:
                            <span id="events-count" class="font-semibold text-gray-800">0</span>
                        </p>

                        <p>
                            📝 Próximas entregas:
                            <span id="upcoming-count" class="font-semibold text-gray-800">0</span>
                        </p>
                    </div>
                </div>

            </div>

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

            if (calendarEvents.length === 0) {
                calendarEvents = [{
                        title: 'Parcial Base de Datos',
                        start: '2026-06-26',
                        description: 'Repasar normalización, consultas SQL y relaciones.',
                        subtasks: [
                            'Leer unidad de normalización',
                            'Resolver ejercicios de SQL',
                            'Practicar joins y consultas'
                        ]
                    },
                    {
                        title: 'TP Programación',
                        start: '2026-06-29',
                        description: 'Completar módulo visual de tareas en Laravel.',
                        subtasks: [
                            'Revisar formulario de tareas',
                            'Probar tarjetas reutilizables',
                            'Validar navegación del dashboard'
                        ]
                    }
                ];
            }

            // DETECCIÓN INTELIGENTE DE MÓVIL (Menor a 768px de ancho)
            const esMovil = window.innerWidth < 768;

            // ESCUDO DE LIMPIEZA ABSOLUTA: Destruye el calendario viejo en memoria para evitar duplicados
            if (window.miCalendarioInstancia) {
                window.miCalendarioInstancia.destroy();
            }

            const calendar = new window.FullCalendar.Calendar(calendarEl, {
                plugins: [
                    window.FullCalendar.dayGridPlugin,
                    window.FullCalendar.timeGridPlugin,
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
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },

                events: calendarEvents,

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
            // Creamos un oyente que vigila el límite de los 768px
            const detectorPantalla = window.matchMedia('(max-width: 767px)');
            
            function ajustarVistaCalendario(e) {
                if (e.matches) {
                    // Si la pantalla se encoge a tamaño celular: cambia a Agenda Semanal y reduce la barra superior
                    calendar.changeView('listWeek');
                    calendar.setOption('height', 380);
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today'
                    });
                } else {
                    // Si la pantalla se estira a tamaño PC: cambia a Vista Mensual y restaura los botones
                    calendar.changeView('dayGridMonth');
                    calendar.setOption('height', 'auto');
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    });
                }
                // Forzamos a FullCalendar a recalcular sus dimensiones internas
                calendar.updateSize();
            }

            // Escuchamos los cambios de tamaño en tiempo real
            detectorPantalla.addEventListener('change', ajustarVistaCalendario);

            // Tus contadores originales se quedan aquí abajo funcionando igual
            eventsCountEl.textContent = calendarEvents.length;
            upcomingCountEl.textContent = calendarEvents.length;


            if (calendarEvents.length > 0) {
                upcomingEventsEl.innerHTML = '';

                calendarEvents.slice(0, 5).forEach(event => {
                    const item = document.createElement('div');

                    item.className = 'rounded-xl bg-violet-50 p-3';

                    item.innerHTML = `
                    <p class="font-semibold text-violeta-moderno">
                        ${event.title}
                    </p>
                    <p class="text-xs text-gray-500">
                        ${event.start ?? 'Sin fecha'}
                    </p>
                `;

                    upcomingEventsEl.appendChild(item);
                });
            }

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
