<?php
session_start();
require_once 'classes/FetchEvent.php'; 

if (!isset($_SESSION['user_id']) && $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}

// Fetch events
$event_id = '';
$list_event_obj = new FetchEvent();
$attendees = $list_event_obj->attendee_list($event_id);

if (!$attendees) {
    echo "No attendees available to export.";
    exit;
}

// Set the CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="All_attendee_list.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Add the column headers
fputcsv($output, ['Sl No', 'Attendee Name', 'Phone', 'Email', 'Opinion', 'Event Name', 'Registered by']);

// Add the event data
$sl_no = 0;
while ($row = mysqli_fetch_assoc($attendees)) {
    $sl_no++;
    fputcsv($output, [
        $sl_no,
        $row['name_attendee'],
        $row['phone_attendee'],
        $row['email_attendee'],
        $row['attendee_opinion'],
        $row['event_name'],
        $row['name_attendee'],
    ]);
}

// Close the output stream
fclose($output);
exit;
?>
