<?php
require_once 'database.php';
require_once 'FetchUser.php';
class UserRegistration{
    private $user_name;
    private $user_phone;
    private $user_email;
    private $user_password;
    private $retype_password;
    private $hashed_password;
    private $retype_hashed_password;

    public $obj;
    // This constractor is for creating an object for the class database where have all the connections(db connection, prepare and execute method)
    public function __construct(){

       $this->obj = Database::getInstance();
    }
    public function adduser($data) {
        header('Content-Type: application/json');

        $errors = [];
        $response = ['success' => false, 'errors' => []];


        $this->user_name = $data['name'];
        $this->user_phone = $data['phone'];
        $this->user_email = $data['email'];
        $this->user_password = $data['password'];
        $this->retype_password = $data['retypepassword'];

        // Hash the password before saving
        $this->hashed_password = password_hash($this->user_password, PASSWORD_BCRYPT);
        $fetch_user_obj = new FetchUser();
        // Fetch all users
        $all_users = $fetch_user_obj->selectuser();
        $email_exists = false;
        $phone_exists = false;

        while ($row = mysqli_fetch_assoc($all_users)) {
            if ($row['email'] === $this->user_email) {
                $email_exists = true;
            }
            if ($row['phone'] === $this->user_phone) {
                $phone_exists = true;
            }
        }


        if (empty($_POST['name'])) {
            $errors['name'] = 'name is required.';
        }
        if (empty($_POST['phone'])) {
            $errors['phone'] = 'phone no is required.';
        }
        if (empty($_POST['email'])) {
            $errors['email'] = 'Email is required.';
        }
        if (empty($_POST['password'])) {
            $errors['password'] = 'Password is required.';
        }
        if (empty($_POST['retypepassword'])) {
            $errors['retypepassword'] = 'Re-type Password is required.';
        }

        if ($this->user_password != $this->retype_password) {
            $errors['retypepassword'] = "Re-type Password doesn't match.";
        }


        if ($email_exists == true) {
            $errors['email'] = 'This email already exists.';
        }
        if ($phone_exists == true) {
            $errors['phone'] = 'This phone number already exists.';
        }
        

        // If there are validation errors, return them
        if (!empty($errors)) {
            $response['errors'] = $errors;
            header('Content-Type: application/json'); 
            echo json_encode($response);
            exit;
        }

    
        
            $role = isset($data['role']) ? 1 :0;
            // Insert query
            $insert_query = "INSERT INTO user (name, phone, email, password, role) VALUES (?, ?, ?, ?, ?)";
            $params = ["sissi", $this->user_name, $this->user_phone, $this->user_email, $this->hashed_password, $role];

            $insert_query_connection = $this->obj->prepareAndExecute($insert_query, $params);



            if ($insert_query_connection) {
                $response['success'] = true;
            } else {
                $response['errors']['general'] = 'Failed to add user!';
            }
            echo json_encode($response);
            exit;

    }
}