<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Encabezado -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
                Calendario Académico
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Visualizá entregas, parciales, finales y actividades académicas.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">

            <!-- Calendario -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100">
                <div id="calendar"></div>
            </div>

            <!-- Panel lateral -->
            <div class="space-y-4">

                <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-3">
                        Próximas entregas
                    </h3>

                    <div id="upcoming-events" class="space-y-3">
                        <p class="text-sm text-gray-400">
                            No hay entregas próximas cargadas.
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-3">
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

        .fc .fc-toolbar-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1f2937;
            text-transform: capitalize;
        }

        .fc .fc-button {
            background: #7C3AED !important;
            border: none !important;
            border-radius: 15px !important;
            padding: 0.7rem 1rem !important;
            font-weight: 700 !important;
            box-shadow: none !important;
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

        .fc .fc-col-header-cell {
            background: #F8FAFC;
            padding: 10px 0;
            font-size: 0.8rem;
            color: #64748B;
            text-transform: uppercase;
        }

        .fc .fc-daygrid-day-number {
            color: #334155;
            font-size: 0.85rem;
            padding: 8px;
        }

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

        .fc .fc-toolbar-chunk {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fc .fc-button-group {
            gap: 6px;
        }

        .fc .fc-button-group .fc-button {
            margin: 0 !important;
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

            const calendar = new window.FullCalendar.Calendar(calendarEl, {
                plugins: [
                    window.FullCalendar.dayGridPlugin,
                    window.FullCalendar.timeGridPlugin,
                    window.FullCalendar.interactionPlugin,
                    window.FullCalendar.listPlugin
                ],

                initialView: 'dayGridMonth',
                locale: 'es',

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    list: 'Agenda'
                },

                headerToolbar: {
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

            calendar.render();

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

    <div id="task-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-xl">

            <div class="flex items-center justify-between mb-4">
                <h2 id="modal-title" class="text-xl font-bold text-gray-800">
                    Tarea
                </h2>

                <button id="close-modal" class="text-gray-400 hover:text-gray-700 text-xl">
                    ✕
                </button>
            </div>

            <div class="space-y-4">

                <div>
                    <p class="text-xs uppercase text-gray-400">
                        Descripción
                    </p>

                    <p id="modal-description" class="text-gray-700">
                        Sin descripción
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Subtareas
                    </p>

                    <div id="modal-subtasks" class="rounded-2xl bg-gray-50 p-4 text-sm text-gray-600">
                        Sin subtareas
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
