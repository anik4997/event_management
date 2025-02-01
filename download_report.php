<?php
session_start();
require_once 'classes/FetchEvent.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}

// Fetch events
$list_event_obj = new FetchEvent();
$events = $list_event_obj->show_events();

if (!$events) {
    echo "No events available to export.";
    exit;
}

// Set the CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="events_list.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Add the column headers
fputcsv($output, ['Sl No', 'Event Name', 'Place', 'Description', 'Event Date', 'Attendees/Max Capacity']);

// Add the event data
$sl_no = 0;
while ($row = mysqli_fetch_assoc($events)) {
    $attendee_data = $list_event_obj->attendee_list($row['id']);
    $attendee_count = mysqli_num_rows($attendee_data);
    $sl_no++;
    fputcsv($output, [
        $sl_no,
        $row['event_name'],
        $row['place'],
        $row['description'],
        $row['event_date'],
        $attendee_count . '/' . $row['max_capacity']
    ]);
}

// Close the output stream
fclose($output);
exit;
?>
