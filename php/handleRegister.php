<?php
require "sqlConfig.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // $ip = $_SERVER['REMOTE_ADDR'];
    $data = json_decode(file_get_contents("php://input"));
    $username = $data -> username;
    $password = $data -> password;

    $username = mysqli_real_escape_string($mysqli, $username);
    $password = mysqli_real_escape_string($mysqli, $password);

    if(is_user_login_size_valid($username, $password)){
        $username_count = check_username_count($username, $password);

        if($username_count > 0){
            send_response("Error, username already taken.");
        }
        else{
            create_user($username, $password);
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = get_user_id($username);
            send_response("User account created successfully.", true);
        }
    } 
}


function check_username_count($username, $password) {
    global $mysqli;
    $username_query = mysqli_query($mysqli, "SELECT COUNT(user_name) FROM users WHERE user_name='$username';");
    $username_count = $username_query->fetch_assoc();
    $username_count = $username_count["COUNT(user_name)"];
    return $username_count;
}

function create_user($username, $password) {
    global $mysqli;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insertUser = mysqli_query($mysqli, 
        "INSERT INTO users (user_name, password_hash)
        VALUES('$username', '$hashed_password');");
}

function get_user_id($username){
    global $mysqli;
    $user_id_query = mysqli_query($mysqli, 
        "SELECT user_id FROM users WHERE user_name='$username';");
    $user_id = $user_id_query->fetch_assoc();
    $user_id = $user_id["user_id"];
    return $user_id;
}

function is_user_login_size_valid($username, $password) {
    if(empty($username) | empty($password)) {
        return false;
    }
    elseif(strlen($username) > 30 | strlen($password) > 100){
        return false;
    }
    else{
        return true;
    }
}

function send_response($response_message, $login = false){
    if($login == false){
        $response = array("responseMessage" => $response_message);
    }
    else{
        $response = array("responseMessage" => $response_message, "login" => $login);
    }
    echo json_encode($response);
}

?>
