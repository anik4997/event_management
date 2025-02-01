<?php
require_once 'database.php';
class FetchEvent{
    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){

       $this->obj = Database::getInstance();
    }

    //  This method is for select query for event list
    public function show_events(){
        $show_event_query = "SELECT * FROM event";
        $params = [];
        $show_event_query_connection = $this->obj->prepareAndExecute($show_event_query, $params); 
        return $show_event_query_connection;
    } 
    public function attendee_list($event_id){
        if($event_id != ''){
            $count_attendee_query = "SELECT attendee_registration.*, event.event_name 
            FROM attendee_registration 
            JOIN event ON attendee_registration.id_event = event.id
            WHERE attendee_registration.id_event = ?";
            $params = ["i", $event_id];
        }else{
            $count_attendee_query = "SELECT attendee_registration.*, event.event_name 
                  FROM attendee_registration 
                  JOIN event ON attendee_registration.id_event = event.id";
                  $params = [];
        }
        $count_attendee_query_connection = $this->obj->prepareAndExecute($count_attendee_query, $params);
        if ($count_attendee_query_connection) {
            return $count_attendee_query_connection;
        }
        return false;
    }
    public function get_event_details($event_id) {
        $query = "SELECT * FROM event WHERE id = ?";
        $params = ['i', $event_id]; 
        $result = $this->obj->prepareAndExecute($query, $params);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
}