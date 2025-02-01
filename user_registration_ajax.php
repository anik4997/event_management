<?php
require_once 'classes/UserRegistration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $add_user = new UserRegistration();
    $response = $add_user->adduser($_POST);
    echo $response; 
    exit;
}