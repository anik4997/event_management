<?php
require_once 'classes/AttendeeRegistration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $attendee_registration = new AttendeeRegistration();
    $response = json_decode($attendee_registration->attendee_registration($_POST), true);

    echo json_encode($response);
    exit;
}
