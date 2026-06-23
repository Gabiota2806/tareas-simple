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

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

window.apiFetch = (url, options = {}) => {
    return fetch(url, {
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            ...(options.headers || {}),
        },
        ...options,
    });
};

Alpine.start();