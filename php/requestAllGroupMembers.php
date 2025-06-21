<?php
require "sqlConfig.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $group_id = $_GET["groupId"];
    $user_id = $_SESSION["user_id"];

    if(isset($user_id, $group_id) && is_numeric($group_id)){
        if(is_group_member($user_id, $group_id)){
            send_all_user_ids($group_id);
        } 
    }
}

function send_all_user_ids($group_id){
    global $mysqli;
    $user_ids_query = mysqli_query($mysqli, "SELECT user_id FROM group_members WHERE groups_id=$group_id");

    class JsonClass{}
    $jsonObject = new JsonClass();

    while($user_ids_row = $user_ids_query->fetch_assoc()) {
        $user_id = $user_ids_row["user_id"];
        $user_id_list[] = $user_id;
    }
    $jsonObject->userIds = $user_id_list;
    $jsonObject = json_encode($jsonObject);
    echo $jsonObject;
}

function is_group_member($user_id, $group_id){
    global $mysqli;
    $group_members_query = mysqli_query($mysqli, "SELECT group_members_id FROM group_members WHERE groups_id=$group_id AND user_id=$user_id;");
    if(mysqli_num_rows($group_members_query) > 0){
        return true;
    }
    else{
        return false;
    }
}
