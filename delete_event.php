<?php
session_start();
require_once 'classes/DeleteEvent.php';
require_once 'classes/FetchUser.php';

$user_id = $_SESSION['user_id'];

$fetch_user_obj = new FetchUser();
$current_user = $fetch_user_obj->get_user_by_id($user_id);

header('Content-Type: application/json'); 

if (!isset($_SESSION['user_id']) || $current_user['role'] != 1) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $delete_event_obj = new DeleteEvent();

    $result = $delete_event_obj->delete_event($event_id);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete event']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
exit;
