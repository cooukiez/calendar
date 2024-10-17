// Function to toggle dark mode based on system theme
function setSystemTheme() {
    // Check if the system's color scheme preference is dark
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        // If true, add the 'ec-dark' class to enable dark mode
        document.body.classList.add('ec-dark');
    } else {
        // Otherwise, remove the 'ec-dark' class to disable dark mode
        document.body.classList.remove('ec-dark');
    }
}

/**
 * @brief   Determines if the current device is a mobile device based on the user agent string.
 *
 * This function checks the user agent, vendor, or browser type (navigator object) to determine
 * if the device is a mobile device. It uses a large regex pattern that matches many known
 * mobile device user agents.
 *
 * @return  {boolean} Returns true if the device is detected as a mobile device, false otherwise.
 */
function isMobileDevice() {
    // Initialize a flag to false, assuming the device is not mobile by default
    let check = false;
    // Immediately invoked function to check the user agent string against a pattern for mobile devices
    (function (a) {
        // Test the user agent (or vendor or window.opera) for various mobile device patterns
        // If a match is found, set `check` to true
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true; // 100% safe
    })(navigator.userAgent || navigator.vendor || window.opera);
    return check;
}

// Call the function when the page loads to set the initial theme
window.addEventListener('load', setSystemTheme);

// Listen for changes in the system theme and toggle dark mode dynamically
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', setSystemTheme);

// CORS proxy to bypass cross-origin restrictions
const corsProxy = 'https://corsproxy.io/?';

// URL of the .ics file for the calendar (Replace with the correct URL)
const icsUrl = 'https://iserv.kkg.berlin/iserv/public/calendar?key=eb329e8158c8ef6188a3cc2de8740c5b';

// Current date for checking event recurrences and determining past events
const currentDate = new Date();
let key; // Placeholder variable for future use (possibly event filtering)

// Function to fetch the ICS file from the provided URL
const fetchICS = async (url) => {
    try {
        // Fetch the ICS file via CORS proxy
        const response = await fetch(corsProxy + url);
        if (!response.ok) {
            // Throw an error if the response is not successful
            throw new Error("Failed to fetch the .ics file");
        }
        // Return the fetched ICS file content as text
        return await response.text();
    } catch (error) {
        // Log any errors that occur during fetch
        console.error("Error fetching the .ics file:", error);
    }
};

// Function to parse ICS data and return an array of event objects
const parseICS = (icsData) => {
    // Parse the ICS data into a jCal object using the ICAL library
    const jcalData = ICAL.parse(icsData);
    const comp = new ICAL.Component(jcalData);
    const events = comp.getAllSubcomponents("vevent"); // Get all event components
    const eventList = []; // Array to store parsed events

    // Iterate over each event in the ICS file
    events.forEach(event => {
        const vevent = new ICAL.Event(event);
        const rrule = vevent.component.getFirstPropertyValue('rrule'); // Recurrence rule (if any)
        const startDate = vevent.startDate.toJSDate(); // Convert start date to JavaScript Date object
        const endDate = vevent.endDate.toJSDate(); // Convert end date to JavaScript Date object
        const location = vevent.component.getFirstPropertyValue('location'); // Event location
        const category = vevent.component.getFirstPropertyValue('categories'); // Event category

        if (rrule) {
            // Handle recurring events using the recurrence rule
            const iterator = rrule.iterator(vevent.startDate);
            let next; // Variable to store the next occurrence of the event

            // Iterate over each recurrence of the event
            while ((next = iterator.next())) {
                const nextEndDate = new Date(next.toJSDate().getTime() + (endDate - startDate)); // Calculate event end time for each recurrence

                // Add each occurrence of the event to the event list
                eventList.push({
                    summary: vevent.summary, // Event title
                    startDate: next.toJSDate(), // Recurrence start date
                    endDate: nextEndDate, // Recurrence end date
                    allDay: vevent.startDate.isDate, // Check if it's an all-day event
                    description: vevent.description, // Event description
                    category: category, // Event category
                    location: location, // Event location
                    pastDate: nextEndDate < currentDate, // Check if the event is in the past
                    raw: event.toString(), // Raw event data for export
                });
            }
        } else {
            // Handle non-recurring events
            eventList.push({
                summary: vevent.summary, // Event title
                startDate: vevent.startDate.toJSDate(), // Event start date
                endDate: vevent.endDate.toJSDate(), // Event end date
                allDay: vevent.startDate.isDate, // Check if it's an all-day event
                description: vevent.description, // Event description
                category: category, // Event category
                location: location, // Event location
                pastDate: vevent.endDate.toJSDate() < currentDate, // Check if the event is in the past
                raw: event.toString(), // Raw event data for export
            });
        }
    });

    return eventList; // Return the list of events
};

// Function to calculate how much of an element is hidden at the bottom of a scrollable view
function getHiddenAmountBottom(scrollView, element) {
    const scrollViewRect = scrollView.getBoundingClientRect(); // Get the dimensions of the scrollable view
    const elementRect = element.getBoundingClientRect(); // Get the dimensions of the element

    // Calculate the bottom of the scroll view and the element
    const scrollViewBottom = scrollViewRect.bottom;
    const elementBottom = elementRect.bottom;

    // Calculate the amount of the element that is hidden at the bottom
    return Math.max(0, elementBottom - scrollViewBottom);
}

// Function to calculate how much of an element is hidden on the left of a scrollable view
function getHiddenAmountLeft(scrollView, element) {
    const scrollViewRect = scrollView.getBoundingClientRect(); // Get the dimensions of the scrollable view
    const elementRect = element.getBoundingClientRect(); // Get the dimensions of the element

    // Calculate the left edge of the scroll view and the element
    const scrollViewLeft = scrollViewRect.left;
    const elementLeft = elementRect.left;

    // Calculate the amount of the element that is hidden on the left
    return Math.min(0, elementLeft - scrollViewLeft);
}

// Create a new div element to display event details and append it to the body
const detailsBox = document.createElement('div');
detailsBox.id = 'event-details-box'; // Set the ID for the details box
detailsBox.classList.add('event-details-box'); // Add a CSS class for styling
detailsBox.style.display = 'none'; // Hide the details box initially
document.body.appendChild(detailsBox); // Append the details box to the document body

// Fetch the ICS file and parse it into events, then initialize the calendar
fetchICS(icsUrl).then(icsData => {
    const events = parseICS(icsData); // Parse the ICS data into event objects

    // Map event categories to specific colors
    const mapCategories = new Map([
        ['Klausur', '#800080'], // Purple for "Klausur" (exam)
        ['Wichtig', '#b32f28'], // Red for "Wichtig" (important)
        ['Ferien', '#2E8B57'], // Green for "Ferien" (vacation)
        ['Veranstaltung', '#a29417'], // Yellow for "Veranstaltung" (event)
    ]);

    // Map event categories to resource IDs for filtering
    const mapResources = new Map([
        ['Alle', 1], // Resource ID 1 for "All"
        ['Klausur', 2], // Resource ID 2 for "Klausur"
        ['Wichtig', 3], // Resource ID 3 for "Wichtig"
        ['Ferien', 4], // Resource ID 4 for "Ferien"
        ['Veranstaltung', 5], // Resource ID 5 for "Veranstaltung"
    ]);

    // Define resources for filtering events by category
    const resources = [
        {id: 1, title: ''}, // Resource for all events
        {id: 2, title: 'Klausur'}, // Resource for exams
        {id: 3, title: 'Wichtig'}, // Resource for important events
        {id: 4, title: 'Ferien'}, // Resource for vacation events
        {id: 5, title: 'Veranstaltung'}, // Resource for general events
    ];

    // Set default color for uncategorized events
    mapCategories.set(null, '#4682B4'); // Steel blue for null category
    mapResources.set(null, 1); // Default resource ID for uncategorized events

    // Initialize the calendar using the EventCalendar library
    const ec = new EventCalendar(document.getElementById('ec'), {
        locale: 'de', // Set the locale to German
        view: 'dayGridMonth', // Set the default view to month

        // Only enable dayGridMonth and Filter views on mobile devices
        headerToolbar: isMobileDevice() ? {
            start: 'prev,next today', // Navigation buttons
            center: 'title', // Title in the center
            end: 'dayGridMonth Filter', // View and filter buttons
        } : {
            start: 'prev,next today', // Navigation buttons
            center: 'title', // Title in the center
            end: 'dayGridMonth,timeGridWeek,timeGridDay listYear,resourceTimelineWeek,Filter', // View and filter buttons
        },

        scrollTime: currentDate.getHours() + ":" + currentDate.getMinutes() + ":" + currentDate.getSeconds(), // Set the scroll position to the current time
        resources: resources, // Set the resources for filtering

        // Add events to the calendar
        events: events.map(event => ({
            title: event.summary, // Event title
            start: event.startDate, // Event start date
            end: event.endDate, // Event end date
            allDay: event.allDay, // Check if it's an all-day event
            extendedProps: {
                description: event.description, // Event description
                location: event.location, // Event location
                category: event.category, // Event category
                raw: event.raw, // Raw event data for export
            },
            id: event.category, // Set the event's category as its ID
            color: mapCategories.get(event.category), // Set the event color based on category
            className: event.pastDate ? 'ec-past-event' : '', // Add a class for past events
            resourceId: mapResources.get(event.category) || 1, // Set the resource ID based on category
        })),

        customButtons: {
            // Define a custom filter button for event categories
            Filter: {
                text: 'Filter', // Button label
                click: function (event) {
                    let dropdown = document.getElementById('dropdown'); // Get the filter dropdown element
                    if (!dropdown) {
                        // Create the dropdown if it doesn't exist
                        dropdown = document.createElement('div');
                        dropdown.id = 'dropdown'; // Set the dropdown ID

                        // Create a button for each category in the resource map
                        for (const ckey of mapResources.keys()) {
                            if (ckey !== null) {
                                const button = document.createElement('button');
                                button.id = ckey; // Set the button ID to the category
                                button.textContent = ckey; // Set the button label to the category
                                button.classList.add('dropbtn'); // Add a class for styling
                                dropdown.appendChild(button); // Add the button to the dropdown
                                dropdown.innerHTML += "<br>"; // Add a line break
                            }
                        }

                        dropdown.classList.add('dropdown'); // Add a class for dropdown styling
                        document.body.appendChild(dropdown); // Append the dropdown to the body

                        // Set the dropdown position relative to the filter button
                        const dropdownWidth = dropdown.offsetWidth;
                        const buttonPosition = event.target.getBoundingClientRect();
                        dropdown.style.top = `${buttonPosition.bottom + window.scrollY}px`; // Align to the bottom of the button
                        dropdown.style.left = `${buttonPosition.right - dropdownWidth + window.scrollX}px`; // Align to the right of the button

                        // Add a click event listener to handle dropdown selections
                        document.addEventListener('click', function outsideClickListener(element) {
                            if (mapResources.has(element.target.id)) {
                                // Filter events based on the selected category
                                for (const pkey of mapResources.keys()) {
                                    if (element.target.id === "Alle") {
                                        // Show all resources
                                        ec.setOption("resources", resources);
                                        ec.setOption("filterEventsWithResources", true);
                                        break;
                                    }
                                    if (pkey === element.target.id) {
                                        // Show events for the selected category
                                        ec.setOption("resources", [{id: mapResources.get(pkey), title: pkey}]);
                                        ec.setOption("filterEventsWithResources", true);
                                        break;
                                    }
                                }
                                // Remove the dropdown and listener after selection
                                dropdown.remove();
                                document.removeEventListener('click', outsideClickListener);
                            }

                            // Remove the dropdown if clicking outside of it
                            if (!dropdown.contains(element.target) && element.target !== event.target) {
                                dropdown.remove();
                                document.removeEventListener('click', outsideClickListener);
                            }
                        });
                    } else {
                        // Remove the dropdown if it's already open
                        dropdown.remove();
                    }
                }
            }
        },

        // Customize button labels
        allDayContent: 'Ganztägig',
        buttonText: {
            listYear: 'Übersicht', // Overview
            dayGridMonth: 'Monat', // Month
            timeGridWeek: 'Woche', // Week
            timeGridDay: 'Tag', // Day
            today: 'Heute', // Today
            resourceTimelineWeek: 'Timeline', // Timeline view
        },

        // Define view-specific settings
        views: {
            timeGridWeek: {
                pointer: true, // Enable pointer support for the weekly grid
                slotLabelFormat: {hour: '2-digit', minute: '2-digit', hour12: false}, // 24-hour time format
            },
            resourceTimeGridWeek: {pointer: true}, // Enable pointer support for resource view
        },

        slotEventOverlap: false, // Prevent events from overlapping
        dayMaxEvents: true, // Limit the number of events shown per day
        nowIndicator: true, // Show a marker for the current time
        firstDay: 1, // Set Monday as the first day of the week

        eventStartEditable: false, // Disable editing event start times
        eventDurationEditable: false, // Disable editing event durations

        // Event click handler (disabled for now)
        eventClick: function (info) {
            return; // Prevent any action for now

            // If enabled: download the event as an .ics file
            info.jsEvent.preventDefault(); // Prevent the default action
            const title = info.event.title; // Get event title

            // Create a Blob from the .ics content
            const blob = new Blob([info.event.extendedProps.raw], {type: 'text/calendar'});
            const url = URL.createObjectURL(blob); // Create a URL for the Blob

            // Create a temporary link to download the .ics file
            const link = document.createElement('a');
            link.href = url;
            link.download = `${title}.ics`; // Set the download filename

            // Append the link, trigger a click to download, and remove the link
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Release the URL object
            URL.revokeObjectURL(url);
        },

        // Event mouse enter handler (shows the event details box)
        eventMouseEnter: function (info) {
            const event = info.event;
            const eventElement = info.el;

            // Populate the details box with event information
            detailsBox.innerHTML = `
                <div class="title">${event.title}</div>
                <div class="desc">${event.extendedProps.description || ''}</div>
                <div class="subtext">
                    ${event.extendedProps.location ? '<span class="nerd-icon">\uf450 </span>' + event.extendedProps.location + '<br>' : ''}
                    ${event.extendedProps.category ? '<span class="nerd-icon">\uea83 </span>' + event.extendedProps.category + '<br>' : ''}
                    ${event.start.toLocaleString()}
                </div>
            `;

            let windowScrollY = document.documentElement.scrollTop;
            const scrollContainer = document.querySelector('.ec-body'); // Get the scrollable container

            // Update the position of the details box based on event location
            function updatePosition() {
                let rect = eventElement.getBoundingClientRect();
                let containerRect = scrollContainer.getBoundingClientRect();

                windowScrollY = document.documentElement.scrollTop;

                // Adjust the box position based on the event element and scroll container
                const hiddenAmountBottom = getHiddenAmountBottom(scrollContainer, eventElement);
                const hiddenAmountLeft = getHiddenAmountLeft(scrollContainer, eventElement);

                detailsBox.style.top = hiddenAmountBottom > 0
                    ? `${scrollContainer.getBoundingClientRect().bottom}px`
                    : `${rect.bottom + window.scrollY}px`;

                detailsBox.style.left = hiddenAmountLeft < 0
                    ? `${scrollContainer.getBoundingClientRect().left}px`
                    : `${rect.left}px`;
            }

            updatePosition(); // Initial position update
            detailsBox.style.display = 'block'; // Show the details box

            // Update position on scroll
            document.addEventListener('scroll', function () {
                updatePosition();
            }, {passive: true});

            scrollContainer.addEventListener('scroll', function () {
                updatePosition();
            }, {passive: true});
        },

        // Event mouse leave handler (hides the event details box)
        eventMouseLeave: function () {
            detailsBox.style.display = 'none'; // Hide the details box
        },
    });

    // If mobile device set title format to numeric year and short month
    if (isMobileDevice()) {
        ec.setOption('titleFormat', {year: 'numeric', month: 'short'});
    }
});
