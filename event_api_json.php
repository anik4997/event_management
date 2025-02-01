<?php
require_once 'classes/FetchEvent.php'; 
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['event_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Event ID is required']);
    exit;
}

$event_id = intval($_GET['event_id']); 

$list_event_obj = new FetchEvent();

$event_details = $list_event_obj->get_event_details($event_id);

if (!$event_details) {
    echo json_encode([
                                'status' => 'error', 
                                'message' => 'Event not found'
                            ], JSON_PRETTY_PRINT);
                            exit;
}

echo json_encode([
                            'status' => 'success', 
                            'data' => $event_details
                        ], JSON_PRETTY_PRINT);
exit;
