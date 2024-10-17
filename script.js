// Function to toggle dark mode based on system theme
function setSystemTheme() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('ec-dark');
    } else {
        document.body.classList.remove('ec-dark');
    }
}

// Call the function when the page loads
window.addEventListener('load', setSystemTheme);

// Listen for changes in the system theme
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', setSystemTheme);

const corsProxy = 'https://corsproxy.io/?';
const icsUrl = 'https://iserv.kkg.berlin/iserv/public/calendar?key=eb329e8158c8ef6188a3cc2de8740c5b'; // Replace with your ICS feed URL
const currentDate = new Date();
let key;

const fetchICS = async (url) => {
    try {
        const response = await fetch(corsProxy + url); // Prepend CORS proxy
        if (!response.ok) {
            throw new Error("Failed to fetch the .ics file");
        }
        return await response.text();
    } catch (error) {
        console.error("Error fetching the .ics file:", error);
    }
};

const parseICS = (icsData) => {
    const jcalData = ICAL.parse(icsData);
    const comp = new ICAL.Component(jcalData);
    const events = comp.getAllSubcomponents("vevent");
    const eventList = [];

    events.forEach(event => {
        const vevent = new ICAL.Event(event);
        const rrule = vevent.component.getFirstPropertyValue('rrule');
        const startDate = vevent.startDate.toJSDate();
        const endDate = vevent.endDate.toJSDate();
        const location = vevent.component.getFirstPropertyValue('location');
        const category = vevent.component.getFirstPropertyValue('categories');

        if (rrule) {
            // Handle recurrence using ical.js and expand the RRULE
            const iterator = rrule.iterator(vevent.startDate);

            let next; // next date for the event
            while ((next = iterator.next())) {
                const nextEndDate = new Date(next.toJSDate().getTime() + (endDate - startDate)); // Calculate duration

                // Create the event for each occurrence
                eventList.push({
                    summary: vevent.summary,
                    startDate: next.toJSDate(),
                    endDate: nextEndDate,
                    allDay: vevent.startDate.isDate,
                    description: vevent.description,
                    category: category,
                    location: location,
                    pastDate: nextEndDate < currentDate,
                });
            }
        } else {
            // Non-recurring event
            eventList.push({
                summary: vevent.summary,
                startDate: vevent.startDate.toJSDate(),
                endDate: vevent.endDate.toJSDate(),
                allDay: vevent.startDate.isDate,
                description: vevent.description,
                category: category,
                location: location,
                pastDate: vevent.endDate.toJSDate() < currentDate,
            });
        }
    });

    return eventList;
};

function getHiddenAmountBottom(scrollView, element) {
    const scrollViewRect = scrollView.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    // Calculate the bottom of the scroll view and the element
    const scrollViewBottom = scrollViewRect.bottom;
    const elementBottom = elementRect.bottom;

    // Calculate the hidden amount
    return Math.max(0, elementBottom - scrollViewBottom);
}

function getHiddenAmountLeft(scrollView, element) {
    const scrollViewRect = scrollView.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    // Calculate the right edge of the scroll view and the element
    const scrollViewLeft = scrollViewRect.left;
    const elementLeft = elementRect.left;

    // Calculate the hidden amount
    return Math.min(0, elementLeft - scrollViewLeft);
}

// Create a new div element for the event details box and append it to the body (once)
const detailsBox = document.createElement('div');
detailsBox.id = 'event-details-box';
detailsBox.classList.add('event-details-box');
detailsBox.style.display = 'none'; // Initially hidden
document.body.appendChild(detailsBox);

// Fetch and parse the ICS data
fetchICS(icsUrl).then(icsData => {
    const events = parseICS(icsData);

    const mapCategories = new Map([
        ['Klausur', '#800080'],
        ['Wichtig', '#b32f28'],
        ['Ferien', '#2E8B57'],
        ['Veranstaltung', '#a29417'],
    ]);

    const mapResources = new Map([
        ['Alle', 1],
        ['Klausur', 2],
        ['Wichtig', 3],
        ['Ferien', 4],
        ['Veranstaltung', 5],
    ]);

    const resources = [
        {id: 1, title: ''},
        {id: 2, title: 'Klausur'},
        {id: 3, title: 'Wichtig'},
        {id: 4, title: 'Ferien'},
        {id: 5, title: 'Veranstaltung'},
    ];

    mapCategories.set(null, '#4682B4');
    mapResources.set(null, 1);

    // Initialize the Event Calendar after the events have been fetched
    const ec = new EventCalendar(document.getElementById('ec'), {
        locale: 'de',

        view: 'dayGridMonth',

        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay listWeek,resourceTimelineWeek,Filter',
        },
        scrollTime: currentDate.getHours() + ":" + currentDate.getMinutes() + ":" + currentDate.getSeconds(),

        resources: resources,

        events: events.map(event => ({
            title: event.summary,
            start: event.startDate,
            end: event.endDate,
            allDay: event.allDay,
            extendedProps: {
                description: event.description,
                location: event.location,
                category: event.category,
            },
            id: event.category,
            color: mapCategories.get(event.category),
            className: event.pastDate ? 'ec-past-event' : '',
            resourceId: mapResources.get(event.category) || 1,
        })),

        customButtons: {
            Filter: {
                text: 'Filter',
                click: function (event) {
                    let dropdown = document.getElementById('dropdown');
                    if (!dropdown) {
                        dropdown = document.createElement('div');
                        dropdown.id = 'dropdown';
                        for (const ckey of mapResources.keys()) {
                            if (ckey !== null) {
                                const button = document.createElement('button');
                                button.id = ckey;
                                button.textContent = ckey;
                                button.classList.add('dropbtn');
                                dropdown.appendChild(button);
                                const lineBreak1 = document.createElement('br');
                                dropdown.appendChild(lineBreak1);
                            }
                        }
                        dropdown.classList.add('dropdown');

                        document.body.appendChild(dropdown);
                        const dropdownWidth = dropdown.offsetWidth;
                        const buttonPosition = event.target.getBoundingClientRect();

                        dropdown.style.top = `${buttonPosition.bottom + window.scrollY}px`; // Align bottom (consider scroll)
                        dropdown.style.left = `${buttonPosition.right - dropdownWidth + window.scrollX}px`;

                        document.addEventListener('click', function outsideClickListener(element) {
                            if (mapResources.has(element.target.id)) {
                                for (const pkey of mapResources.keys()) {
                                    if (element.target.id === "Alle") {
                                        ec.setOption("resources", resources);
                                        ec.setOption("filterEventsWithResources", true);
                                        break;
                                    }
                                    if (pkey === element.target.id) {
                                        ec.setOption("resources", [{id: mapResources.get(pkey), title: pkey}]);
                                        ec.setOption("filterEventsWithResources", true);
                                        break;
                                    }
                                }
                                dropdown.remove();
                                document.removeEventListener('click', outsideClickListener);
                            }

                            if (!dropdown.contains(element.target) && element.target !== event.target) {
                                dropdown.remove();
                                document.removeEventListener('click', outsideClickListener);
                            }
                        });
                    } else {
                        dropdown.remove();
                    }
                }
            }
        },

        allDayContent: 'Ganztägig',

        buttonText: {
            listWeek: 'Übersicht',
            dayGridMonth: 'Monat',
            timeGridWeek: 'Woche',
            timeGridDay: 'Tag',
            today: 'Heute',
            resourceTimelineWeek: 'Timeline',
        },

        views: {
            timeGridWeek: {
                pointer: true,
                slotLabelFormat: {hour: '2-digit', minute: '2-digit', hour12: false}, // 24h time format
            },
            resourceTimeGridWeek: {pointer: true},
            //slotWidth: 100,
        },

        slotEventOverlap: false,
        dayMaxEvents: true,
        nowIndicator: true,

        firstDay: 1, // Monday

        eventStartEditable: false, // Disable editing the start time
        eventDurationEditable: false, // Disable editing the duration

        eventClick: function (info) {
            alert(info.event.title + ' clicked.');
        },

        eventMouseEnter: function (info) {
            const event = info.event;
            const eventElement = info.el;

            detailsBox.innerHTML = `
                    <div class="title">${event.title}</div>
                    <div style="width: 100%">
                    <div class="desc">${event.extendedProps.description || ''}</div>
                    <div class="subtext">
                        ${event.extendedProps.location ? '<span class="nerd-icon">\uf450 </span>' + event.extendedProps.location + '<br>' : ''}
                        ${event.extendedProps.category ? '<span class="nerd-icon">\uea83 </span>' + event.extendedProps.category + '<br>' : ''}
                        ${event.start.toLocaleString()}
                    </div>
                    </div>
                `;

            let windowScrollY = document.documentElement.scrollTop;
            const scrollContainer = document.querySelector('.ec-body');

            function updatePosition() {
                let rect = eventElement.getBoundingClientRect();
                let containerRect = scrollContainer.getBoundingClientRect();

                windowScrollY = document.documentElement.scrollTop;

                const hiddenAmountBottom = getHiddenAmountBottom(scrollContainer, eventElement);
                const hiddenAmountLeft = getHiddenAmountLeft(scrollContainer, eventElement);

                if (hiddenAmountBottom > 0) {
                    detailsBox.style.top = `${containerRect.bottom}px`;
                } else {
                    detailsBox.style.top = `${rect.bottom + windowScrollY}px`;
                }

                if (hiddenAmountLeft < 0) {
                    detailsBox.style.left = `${containerRect.left}px`;
                } else {
                    detailsBox.style.left = `${rect.left}px`;
                }
            }

            updatePosition();
            detailsBox.style.display = 'block';

            document.addEventListener('scroll', function () {
                updatePosition();
            }, {passive: true});

            scrollContainer.addEventListener('scroll', function () {
                updatePosition();
            }, {passive: true});
        },

        eventMouseLeave: function () {
            detailsBox.style.display = 'none';
        },
    });
    console.log(ec.getOption("resources"))
});

