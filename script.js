import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid'; // For month view
import iCalendarPlugin from '@fullcalendar/icalendar'; // For .ics support

// Target the div where the calendar will be rendered
const calendarEl = document.getElementById('calendar');

const calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, iCalendarPlugin], // Include plugins for month view and iCalendar
    initialView: 'dayGridMonth', // Month view by default
    events: {
        url: 'https://iserv.kkg.berlin/iserv/public/calendar?key=eb329e8158c8ef6188a3cc2de8740c5b', // URL to the .ics file
        format: 'ics' // Let FullCalendar know it's an .ics file
    }
});

// Render the calendar
calendar.render();
