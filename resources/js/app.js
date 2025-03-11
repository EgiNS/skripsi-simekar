import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

// Ekspor FullCalendar ke global scope agar bisa diakses di Blade
window.FullCalendar = { Calendar, dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin };

console.log("FullCalendar loaded", window.FullCalendar);