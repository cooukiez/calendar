<?php
require 'vendor/autoload.php';

use ICal\ICal;

// Load the .ics file
$ical = new ICal('public.ics');

// Fetch events from the .ics file
$events = $ical->events();

// Prepare an array to store FullCalendar-compatible events
$fullCalendarEvents = [];

foreach ($events as $event) {
    $fullCalendarEvents[] = [
        'title' => $event->summary,
        'start' => $event->dtstart_array[2], // Format: 'Y-m-d H:i:s'
        'end' => $event->dtend_array[2],     // Optional: Include if you want to show end times
    ];
}

// Output events as JSON for FullCalendar
header('Content-Type: application/json');
echo json_encode($fullCalendarEvents);
