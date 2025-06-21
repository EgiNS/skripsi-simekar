import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import idLocale from '@fullcalendar/core/locales/id';

// Ekspor FullCalendar ke global scope agar bisa diakses di Blade
window.FullCalendar = { Calendar, dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, idLocale };