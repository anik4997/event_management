<?php
require_once 'classes/Login.php';
require_once 'classes/FetchUser.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email'])) {
        $response["errors"]["email"] = "Email is required.";
    }
    if (empty($_POST['password'])) {
        $response["errors"]["password"] = "Password is required.";
    }
    if (empty($_POST['g-recaptcha-response'])) {
        $response["errors"]["captcha"] = "Please complete the reCAPTCHA.";
    }

    if (empty($response["errors"])) {
        $validation = new Login();
        $user_id = $validation->validation($_POST);

        if ($user_id) {
            // reCAPTCHA verification
            $recaptchaSecret = '6LcNDfopAAAAAHDF1qJ76t9DEsnupXmHkmA-BlOC';
            $recaptchaResponse = $_POST['g-recaptcha-response'];
            
            $recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
            $responseKeys = json_decode($recaptchaVerify, true);

            if (!empty($responseKeys["success"]) && $responseKeys["success"]) {
                $fetch_user_obj = new FetchUser();
                $current_user = $fetch_user_obj->get_user_by_id($user_id);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $current_user['role'];

                $response = ["success" => true, "message" => "Login successful"];
            } else {
                $response = ["success" => false, "message" => "Please complete the reCAPTCHA."];
            }
        }else {
            $response["errors"]["password"] = "Invalid email or password.";
        }
    } 

    echo json_encode($response);
    exit;
}
