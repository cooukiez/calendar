<?php
// Set the ICS URL
$icsUrl = 'https://iserv.kkg.berlin/iserv/public/calendar?key=eb329e8158c8ef6188a3cc2de8740c5b';

// Fetch the ICS content
$icsContent = file_get_contents($icsUrl);

// Check if the content was retrieved successfully
if ($icsContent === false) {
    die('Error fetching the ICS file.');
}

// Parse the ICS content
$events = [];

$lines = explode("\n", $icsContent);
$currentEvent = null;

foreach ($lines as $line) {
    // Trim whitespace
    $line = trim($line);

    // Start a new event
    if (strpos($line, 'BEGIN:VEVENT') !== false) {
        $currentEvent = [];
    }

    // End the current event
    if (strpos($line, 'END:VEVENT') !== false) {
        if ($currentEvent) {
            $events[] = $currentEvent;
        }
        $currentEvent = null;
    }

    // Add event properties
    if ($currentEvent !== null) {
        if (strpos($line, 'SUMMARY:') === 0) {
            $currentEvent['summary'] = substr($line, strlen('SUMMARY:'));
        } elseif (strpos($line, 'DTSTART:') === 0) {
            $currentEvent['start'] = substr($line, strlen('DTSTART:'));
        } elseif (strpos($line, 'DTEND:') === 0) {
            $currentEvent['end'] = substr($line, strlen('DTEND:'));
        } elseif (strpos($line, 'LOCATION:') === 0) {
            $currentEvent['location'] = substr($line, strlen('LOCATION:'));
        }
    }
}

// Display the events in HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Events</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .event { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .summary { font-weight: bold; }
        .details { margin-top: 5px; }
    </style>
</head>
<body>

<h1>Upcoming Events</h1>

<?php if (empty($events)): ?>
    <p>No events found.</p>
<?php else: ?>
    <?php foreach ($events as $event): ?>
        <div class="event">
            <div class="summary"><?php echo htmlspecialchars($event['summary'] ?? ''); ?></div>
            <div class="details">
                <strong>Start:</strong> <?php echo htmlspecialchars($event['start'] ?? ''); ?><br>
                <strong>End:</strong> <?php echo htmlspecialchars($event['end'] ?? ''); ?><br>
                <strong>Location:</strong> <?php echo htmlspecialchars($event['location'] ?? ''); ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>