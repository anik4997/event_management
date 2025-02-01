<?php
require_once 'database.php';
class AddEvent{
    private $user_id; 
    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){
       $this->obj = Database::getInstance();
    }

    // Add event
    public function addevent($event_data) {

        header('Content-Type: application/json');
        ob_clean();

        $errors = [];
        $response = ['success' => false, 'errors' => []];

        // Validate inputs
        if (empty($_POST['event_name'])) {
            $errors['event_name'] = 'Event name is required.';
        }
        if (empty($_POST['place'])) {
            $errors['place'] = 'Place is required.';
        }
        if (empty($_POST['max_capacity'])) {
            $errors['max_capacity'] = 'Max capacity is required.';
        }
        if (empty($_POST['event_description'])) {
            $errors['event_description'] = 'Event description is required.';
        }
        if (empty($_POST['event_date'])) {
            $errors['event_date'] = 'Event date is required.';
        }

        if (!empty($errors)) {
            $response['errors'] = $errors;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $event_name = $event_data['event_name'];
        $place = $event_data['place'];
        $max_capacity = $event_data['max_capacity'];
        $event_description = $event_data['event_description'];
        $event_date = $event_data['event_date'];
    
        // Insert query
        $event_insert_query = "INSERT INTO event (event_name, place, max_capacity, description, event_date) VALUES (?, ?, ?, ?, ?)";

        $params = ["ssiss", $event_name, $place, $max_capacity, $event_description, $event_date];
        $event_insert_query_connection = $this->obj->prepareAndExecute($event_insert_query, $params);//This method is from database.php file

        if ($event_insert_query_connection) {
            $response['success'] = true;
        } else {
            $response['errors']['general'] = 'Failed to add event!';
        }

        ob_clean(); 
        return json_encode($response);
    }
}