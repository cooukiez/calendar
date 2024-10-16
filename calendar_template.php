<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Custom Calendar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>My Custom Calendar</h1>
    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($eventData)): ?>
                <?php foreach ($eventData as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['summary']); ?></td>
                        <td><?= htmlspecialchars($event['dtstart']); ?></td>
                        <td><?= htmlspecialchars($event['dtend']); ?></td>
                        <td><?= htmlspecialchars($event['location']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No events available</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
