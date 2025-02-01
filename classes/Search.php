<?php
require_once 'database.php';
class Search{
    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){

       $this->obj = Database::getInstance();
    }
    // Filter event
    public function event_search($search_input){
        $from_date = $search_input['start_date'];
        $to_date = $search_input['end_date'];
        $search_query = "SELECT * FROM event WHERE event_date >= ? AND event_date <= ?";
        $params = ["ss", $from_date, $to_date];
        $search_query_connection = $this->obj->prepareAndExecute($search_query, $params);
         
        if ($search_query_connection) {
            return $search_query_connection;
        } else {
            return false;
            }
        }      
}