<?php
require "sqlConfig.php";
session_start();

const FILE_DIR = "../uploads/";
const FILE_SIZE_LIMIT = 5000000; // ~5MB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /* $ip = $_SERVER['REMOTE_ADDR']; */
    $timestamp = gmdate("Y-m-d H:i:s");
    $task_id = $_POST["taskId"];
    $user_id = $_SESSION["user_id"];

    if (isset($task_id, $user_id) && is_numeric($task_id)){

        $sent_task_id = query_sent_task_id($task_id, $user_id);
        if($sent_task_id == ""){
            send_error_message("ERROR: Task does not exist or user does not have access to task.");
            exit();
        }

        $sent_task_status = query_sent_task_status($sent_task_id);
        if($sent_task_status == true){
            send_error_message("ERROR: Task was already sent");
            exit();
        }
        else{
            handle_task_completion($sent_task_id, $timestamp);
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


function handle_task_completion($sent_task_id, $timestamp){
    global $mysqli, $file_id;
    if(handle_file_upload()){
        mysqli_query($mysqli, "UPDATE sent_tasks SET file_id = $file_id WHERE sent_task_id = $sent_task_id;");
        mysqli_query($mysqli, "UPDATE sent_tasks SET sent_task_status = true WHERE sent_task_id = $sent_task_id;");
        mysqli_query($mysqli, "UPDATE sent_tasks SET sent_task_timestamp = '$timestamp' WHERE sent_task_id = $sent_task_id;");
        send_task_status_response(true, $timestamp);
    }
}

function handle_file_upload(){
    global $mysqli, $file_id;
    if (isset($_FILES["file"])){
        $file_name = mysqli_real_escape_string($mysqli, basename($_FILES["file"]["name"]));
        $file_tmp_name = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_mime_type = mime_content_type($file_tmp_name);

        if ($file_size > FILE_SIZE_LIMIT) {
            send_error_message("ERROR: File failed to upload, file size exceeded " . FILE_SIZE_LIMIT);
            return false;
        }

        mysqli_query($mysqli, 
            "INSERT INTO files (file_name, file_size, file_mime_type)
            VALUES('$file_name', $file_size, '$file_mime_type');");
        $file_id_query = mysqli_query($mysqli, "SELECT MAX(file_id) FROM files WHERE file_name='$file_name' AND file_size=$file_size;");
        $file_id = $file_id_query->fetch_assoc();
        $file_id = $file_id["MAX(file_id)"];
        $file_full_path = FILE_DIR . $file_id;

        if(is_file($file_full_path)) {
            /* echo "ERROR: File path already exists"; */
            return false;
        }

        if(move_uploaded_file($file_tmp_name, $file_full_path)){
            /* echo "File sucessfully uploaded"; */
            return true;
        }

        else{
            mysqli_query($mysqli, "DELETE FROM files WHERE file_id=$file_id;");
            send_error_message("ERROR: Failed to upload file");
            return false;
        }
    }
}

function send_error_message($error_message){
    class JsonClass{}
    $json_object = new JsonClass();
    $json_object->errorMessage = $error_message;
    $json_object = json_encode($json_object);
    echo $json_object;
}

function send_task_status_response($task_status, $task_timestamp){
    class JsonClass{}
    $json_object = new JsonClass();
    $json_object->taskStatus = $task_status;
    $json_object->taskTimestamp = $task_timestamp;
    $json_object = json_encode($json_object);
    echo $json_object;
}
?>
