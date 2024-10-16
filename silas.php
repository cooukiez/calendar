<?php
require 'vendor/autoload.php'; // Autoload for php-ics-parser

use Kigkonsult\Icalcreator\Vcalendar;

// Fetch and parse the .ics file
$ics_url = 'https://iserv.kkg.berlin/iserv/public/calendar?key=eb329e8158c8ef6188a3cc2de8740c5b';
$ics_content = file_get_contents($ics_url);
file_put_contents('calendar.ics', $ics_content);

$file = 'calendar.ics';
$vcalendar = Vcalendar::factoryFromFile($file);

// Loop through events
foreach ($vcalendar->getComponents() as $component) {
    if ('vevent' === strtolower($component->getCompType())) {
        $summary = $component->getProperty('summary');
        $start = $component->getProperty('dtstart');
        $end = $component->getProperty('dtend');

        echo "Event: $summary\n";
        echo "Start: " . $start['value'] . "\n";
        echo "End: " . $end['value'] . "\n";
    }
}


// Pass the event data to the HTML template
include 'calendar_template.php';
