<?php
require_once 'classes/AddEvent.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $add_event = new AddEvent();
    $response = json_decode($add_event->addevent($_POST), true);
    echo json_encode($response); 
    exit;
}
