<?php
require_once 'database.php';
require_once 'FetchEvent.php';
class AttendeeRegistration{
    private $user_id; 
    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){

       $this->obj = Database::getInstance();
    }

    public function attendee_registration($attendee_registration) {

        header('Content-Type: application/json');
        ob_clean();

        $event_id = $attendee_registration['event_id'];
        $fetch_event_obj = new FetchEvent();
        $attendee_data = $fetch_event_obj->attendee_list($event_id);

        $errors = [];
        if (empty($_POST['name_attendee'])) {
            $errors['name_attendee'] = "Name is required!";
        }

        if (empty($_POST['phone_attendee'])) {
            $errors['phone_attendee'] = "Phone number is required!";
        } elseif (!preg_match('/^\d{10,15}$/', $_POST['phone_attendee'])) {
            $errors['phone_attendee'] = "Enter a valid phone number!";
        }

        if (empty($_POST['email_attendee'])) {
            $errors['email_attendee'] = "Email is required!";
        } elseif (!filter_var($_POST['email_attendee'], FILTER_VALIDATE_EMAIL)) {
            $errors['email_attendee'] = "Enter a valid email address!";
        }

        while($row = mysqli_fetch_assoc($attendee_data)){
            if ($_POST['email_attendee'] == $row['email_attendee']) {
                $errors['email_attendee'] = "This email is already registered";
            }
            if ($_POST['phone_attendee'] == $row['phone_attendee']) {
                $errors['phone_attendee'] = "This phone no is already registered";
            }
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        $response = ['success' => false, 'errors' => []];
    
        $name_attendee = $attendee_registration['name_attendee'];
        $phone_attendee = $attendee_registration['phone_attendee'];
        $email_attendee = $attendee_registration['email_attendee'];
        $attendee_opinion = $attendee_registration['attendee_opinion'];
        $user_id = $attendee_registration['user_id'];
    
        $fetch_event_query = "SELECT max_capacity FROM event WHERE id = ?";
        $params = ["i", $event_id];
        $fetch_event_result = $this->obj->prepareAndExecute($fetch_event_query, $params);
        $event_data = mysqli_fetch_assoc($fetch_event_result);
    
        if ($event_data) {
            $max_capacity = $event_data['max_capacity'];
    
            $current_attendee_count = ($attendee_data) ? mysqli_num_rows($attendee_data) : 0;
    
            if ($current_attendee_count >= $max_capacity) {
                $response['errors']['general'] = "Registration failed! The event has reached its maximum capacity.";
                return json_encode($response);
            }
    
            // Check if the user has already registered
            $check_prev_info_query = "SELECT * FROM attendee_registration WHERE user_id = ? AND id_event = ?";
            $params = ["ii", $user_id, $event_id];
            $check_prev_info_query_connection = $this->obj->prepareAndExecute($check_prev_info_query, $params);
    
            if (mysqli_num_rows($check_prev_info_query_connection) == 0) {
                $attendee_insert_query = "INSERT INTO attendee_registration (name_attendee, phone_attendee, email_attendee, attendee_opinion, user_id, id_event) VALUES (?, ?, ?, ?, ?, ?)";
                $params = ["sissii",$name_attendee, $phone_attendee, $email_attendee, $attendee_opinion, $user_id, $event_id ];
                $attendee_insert_query_connection = $this->obj->prepareAndExecute($attendee_insert_query, $params);
    
                if ($attendee_insert_query_connection) {
                    $response['success'] = true;
                } else {
                    $response['errors']['general'] = 'Failed to register this event!';
                }
            } else {
                $response['errors']['general'] = "You have already registered as an attendee for this event.";
            }
        } else {
            $response['errors']['general'] = "Event not found!";
        }
        
        ob_clean(); 
        return json_encode($response);
    }
    
    public function get_previous_registration($user_id, $event_id) {
            $query = "SELECT * FROM attendee_registration WHERE user_id = ? AND id_event = ?";
            $params = ["ii", $user_id, $event_id];
            $result = $this->obj->prepareAndExecute($query, $params);
            return $result;
        }
    }