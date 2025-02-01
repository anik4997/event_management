<?php
require_once 'classes/UpdateEvent.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_event = new UpdateEvent();
    $response = json_decode($update_event->update_event($_POST), true); 

    echo json_encode($response); 
    exit;
}