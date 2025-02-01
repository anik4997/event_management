<?php
require_once 'database.php';
class DeleteEvent{
    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){

       $this->obj = Database::getInstance();
    }

    // Delete event
    public function delete_event($event_id){
        //delete query
        $delete_query = "DELETE FROM event WHERE id = ?";
        $params = ["i", $event_id];
        $delete_query_connection = $this->obj->prepareAndExecute($delete_query, $params);

        if ($delete_query_connection) {
            return true;
        } else {
            return false;
        }
    }
    
}