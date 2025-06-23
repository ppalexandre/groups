<?php
require "sqlConfig.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //$ip = $_SERVER['REMOTE_ADDR'];
    $user_name = $_POST["userName"];
    $password = $_POST["password"];

    $user_name = mysqli_real_escape_string($mysqli, $user_name);
    $password = mysqli_real_escape_string($mysqli, $password);

    if(!empty($user_name) && !empty($password)) {
        check_login($user_name, $password);
    } 
}

function check_login($user_name, $password) {
    global $mysqli;
    $password_hash_query = mysqli_query($mysqli, 
        "SELECT password_hash FROM users WHERE user_name='$user_name';");
    $password_hash = $password_hash_query->fetch_assoc();
    $password_hash = $password_hash["password_hash"];

    if (empty($password_hash)) {
        send_response("Error: Username not found");
    }

    else{
        $is_password_correct = password_verify($password, $password_hash);

        if ($is_password_correct){
            $_SESSION["logged_in"] = true;
            $_SESSION["user_name"] = $user_name;
            $_SESSION["user_id"] = get_user_id($user_name);
            send_response("Login successful", true);
        }
        else {
            send_response("Error: Incorrect password");
        }
    }
}

function get_user_id($user_name){
    global $mysqli;
    $user_id_query = mysqli_query($mysqli, 
        "SELECT user_id FROM users WHERE user_name='$user_name';");
    $user_id = $user_id_query->fetch_assoc();
    $user_id = $user_id["user_id"];
    return $user_id;
}

function send_response($response_message, $login = false){
    $response = array("responseMessage" => $response_message, "login" => $login);
    echo json_encode($response);
}

?>
