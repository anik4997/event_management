<?php
require_once 'database.php';
class UpdateEvent{
    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){

       $this->obj = Database::getInstance();
    }

    // Fetches event details by ID
    public function get_event_by_id($event_id) {
        $query = "SELECT * FROM event WHERE id = ?";
        $params = ["i", $event_id];
        
        $result = $this->obj->prepareAndExecute($query, $params);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Update event details
    public function update_event($event_data) {

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
        if ($_POST['max_capacity'] <= $_POST['attendee_count']) {
            $errors['max_capacity'] = 'Please insert number greater than total attendees';
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

        $event_id = $event_data['event_id'];
        $event_name = $event_data['event_name'];
        $place = $event_data['place'];
        $max_capacity = $event_data['max_capacity'];
        $description = $event_data['event_description'];
        $event_date = $event_data['event_date'];
        

        $update_event_query = "UPDATE event SET event_name = ?, place = ?, max_capacity = ?, description = ?,  event_date = ? WHERE id = ?";

        $params = ["ssissi", $event_name, $place, $max_capacity, $description, $event_date, $event_id];
        $update_query_connection = $this->obj->prepareAndExecute($update_event_query, $params);
        
        if ($update_query_connection) {
            $response['success'] = true;
        } else {
            $response['errors']['general'] = 'Failed to update this event!';
        }

        ob_clean(); 
        return json_encode($response);
    }
}