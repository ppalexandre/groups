<?php
require "sqlConfig.php";
session_start();

const FILE_DIR = "../uploads/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /* $ip = $_SERVER['REMOTE_ADDR']; */
    $task_id = $_POST["taskId"];
    $user_id = $_SESSION["user_id"];

    if (isset($task_id, $user_id) && is_numeric($task_id)){

        $sent_task_id = query_sent_task_id($task_id, $user_id);
        if($sent_task_id == ""){
            send_error_message("ERROR: Task does not exist or user does not have access to task.");
            exit();
        }

        $sent_task_status = query_sent_task_status($sent_task_id);
        if($sent_task_status == false){
            send_error_message("ERROR: Task was never sent");
            exit();
        }
        else{
            handle_sent_task_deletion($sent_task_id);
        }
    }
}

function query_sent_task_id($task_id, $user_id){
    global $mysqli;
    // doubles as authentication
    $sent_task_id_query = mysqli_query($mysqli, "SELECT sent_task_id FROM sent_tasks WHERE task_id=$task_id AND user_id=$user_id;");
    $sent_task_id = $sent_task_id_query->fetch_assoc();
    $sent_task_id = $sent_task_id["sent_task_id"];
    return $sent_task_id;
}

function query_sent_task_status($sent_task_id){
    global $mysqli;
    $task_status_query = mysqli_query($mysqli, "SELECT sent_task_status FROM sent_tasks WHERE sent_task_id=$sent_task_id;");
    $task_status = $task_status_query->fetch_assoc();
    $task_status = $task_status["sent_task_status"];
    return $task_status;
}


function handle_sent_task_deletion($sent_task_id){
    global $mysqli;

    $file_id_query = mysqli_query($mysqli, "SELECT file_id FROM sent_tasks WHERE sent_task_id = $sent_task_id;");
    $file_id = $file_id_query->fetch_assoc();
    $file_id = $file_id["file_id"];

    if(unlink(FILE_DIR . $file_id)){
        mysqli_query($mysqli, "UPDATE sent_tasks SET sent_task_timestamp = null, sent_task_status = false, file_id = null WHERE sent_task_id = $sent_task_id;");
        mysqli_query($mysqli, "DELETE FROM files WHERE file_id = $file_id;");
    }
    else{
        send_error_message("ERROR: File failed to be deleted");
        exit();
    }

    send_task_status_response(false);
}

function send_error_message($error_message){
    class JsonClass{}
    $json_object = new JsonClass();
    $json_object->errorMessage = $error_message;
    $json_object = json_encode($json_object);
    echo $json_object;
}

function send_task_status_response($task_status){
    class JsonClass{}
    $json_object = new JsonClass();
    $json_object->taskStatus = $task_status;
    $json_object = json_encode($json_object);
    echo $json_object;
}
?>
