<?php
require "sqlConfig.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // $ip = $_SERVER['REMOTE_ADDR'];
    $user_name = $_POST["userName"];
    $password = $_POST["password"];

    $user_name = mysqli_real_escape_string($mysqli, $user_name);
    $password = mysqli_real_escape_string($mysqli, $password);

    if(is_user_login_size_valid($user_name, $password)){
        $user_name_count = check_user_name_count($user_name, $password);

        if($user_name_count > 0){
            send_response("Error, user_name already taken.");
        }
        else{
            create_user($user_name, $password);
            $user_id = get_user_id($user_name);
            $_SESSION["logged_in"] = true;
            $_SESSION["user_name"] = $user_name;
            $_SESSION["user_id"] = $user_id;
            add_to_default_group(1, $user_id); // temporary
            send_response("User account created successfully.", true);
        }
    } 
}

// temporary until i properly add groups
function add_to_default_group($group_id, $user_id){
    global $mysqli;
    mysqli_query($mysqli, "INSERT INTO group_members (groups_id, user_id) VALUES ($group_id, $user_id);");
}

function check_user_name_count($user_name, $password) {
    global $mysqli;
    $user_name_query = mysqli_query($mysqli, "SELECT COUNT(user_name) FROM users WHERE user_name='$user_name';");
    $user_name_query = $user_name_query->fetch_assoc();
    $user_name_count = $user_name_query["COUNT(user_name)"];
    return $user_name_count;
}

function create_user($user_name, $password) {
    global $mysqli;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insertUser = mysqli_query($mysqli, 
        "INSERT INTO users (user_name, password_hash)
        VALUES('$user_name', '$hashed_password');");
}

function get_user_id($user_name){
    global $mysqli;
    $user_id_query = mysqli_query($mysqli, 
        "SELECT user_id FROM users WHERE user_name='$user_name';");
    $user_id = $user_id_query->fetch_assoc();
    $user_id = $user_id["user_id"];
    return $user_id;
}

function is_user_login_size_valid($user_name, $password) {
    if(empty($user_name) | empty($password)) {
        return false;
    }
    elseif(strlen($user_name) > 30 | strlen($password) > 100){
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
