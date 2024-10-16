<?php
require 'vendor/autoload.php'; // Autoload for php-ics-parser

use ICal\ICal;

try {
    // Fetch and parse the .ics file
    $ics_url = 'https://iserv.kkg.berlin/iserv/public/calendar?key=eb329e8158c8ef6188a3cc2de8740c5b';
    $ics_content = file_get_contents($ics_url);
    if (!$ics_content) {
        throw new Exception('Unable to fetch the calendar data.');
    }
    file_put_contents('calendar.ics', $ics_content);

    // Parse the .ics file
    $ical = new ICal('calendar.ics');
    $events = $ical->events();

    // Store event data in a simple array for easier HTML rendering
    $eventData = [];
    foreach ($events as $event) {
        $eventData[] = [
            'summary' => $event->summary,
            'dtstart' => $event->dtstart,
            'dtend' => $event->dtend,
            'location' => $event->location
        ];
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    die();
}

// Pass the event data to the HTML template
include 'calendar_template.php';