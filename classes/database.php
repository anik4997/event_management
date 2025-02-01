<?php
require_once 'singleton.php';
class Database {
    // Achieving singleton behavior for this class by singleton trait from'singleton.php' file
    use \singleton;

    private $db_host = 'localhost';
    private $db_username = 'root';
    private $db_password = '';
    private $db_name = 'event_management';
    private $db_conn;

    public static function db_connect() {
        $db = self::getInstance();
        $db->db_conn = mysqli_connect($db->db_host, $db->db_username, $db->db_password, $db->db_name);
        if (!$db->db_conn) {
            die("Not connected" . mysqli_error($db->db_conn)); // Pass the connection object to mysqli_error()
        }
        return $db->db_conn;
    }
    

    public function prepareAndExecute($query, $params) {
        $stmt = $this->db_conn->prepare($query);
        if ($stmt === false) {
            die(json_encode(["success" => false, "errors" => ["general" => "Prepare failed: " . $this->db_conn->error]]));
        }
    
        if (!empty($params)) {
            $stmt->bind_param(...$params);
        }
    
        $execute_result = $stmt->execute();
    
        if ($execute_result === false) {
            die(json_encode(["success" => false, "errors" => ["general" => "Execute failed: " . $stmt->error]]));
        }
    
        // For SELECT queries
        if (strpos(strtoupper($query), 'SELECT') !== false) {
            $result = $stmt->get_result();  
            $stmt->close();
            return $result;  
        }
    
        // For INSERT, UPDATE, DELETE
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
    
        return $affected_rows > 0;
    }
}

// Get a single instance of the database object
$db = Database::getInstance();

// Establish database connection
$db_conn = $db->db_connect();