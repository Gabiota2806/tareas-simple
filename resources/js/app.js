import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import Swal from 'sweetalert2';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

window.Alpine = Alpine;
window.Sortable = Sortable;
window.Swal = Swal;

window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    timeGridPlugin,
    interactionPlugin,
    listPlugin,
};

Alpine.start();