<?php
require "sqlConfig.php";
session_start();

const FILE_DIR = "../uploads/";
const FILE_SIZE_LIMIT = 5000000; // ~5MB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // $ip = $_SERVER['REMOTE_ADDR'];

    $task_title = $_POST["taskTitle"];
    $task_body = $_POST["taskBody"];
    $task_creation_date = gmdate("Y-m-d H:i:s");
    $task_deadline_date = $_POST["taskDeadlineDate"];
    $group_id = $_POST["groupId"];
    $sent_task_user_ids = $_POST["sentTaskUserIds"];
    $user_id = $_SESSION["user_id"];
    
    if(isset($user_id, $task_title, $task_body, $task_deadline_date, $group_id, $sent_task_user_ids) && is_numeric($group_id)){
        $sent_task_user_ids = json_decode($sent_task_user_ids);
        $task_title = mysqli_real_escape_string($mysqli, $task_title);
        $task_body = mysqli_real_escape_string($mysqli, $task_body);
        $task_deadline_date = mysqli_real_escape_string($mysqli, $task_deadline_date);
        $task_deadline_date = gmdate("Y-m-d H:i:s", $task_deadline_date);
        
        if(strtotime($task_creation_date) > strtotime($task_deadline_date)){
            send_error_message("ERROR: Task creation date is bigger than the deadline");
            exit();
        }
        else if(!is_group_member($user_id, $group_id)){
            send_error_message("ERROR: User is not a member of specified group");
            exit();
        }
        else if(handle_file_upload()){
            create_task($user_id, $task_title, $task_body, $task_creation_date, $task_deadline_date, $group_id, $sent_task_user_ids);
            exit();
        }
        else{
            exit();
        }
    }
}

function create_task($user_id, $task_title, $task_body, $task_creation_date, $task_deadline_date, $group_id, $sent_task_user_ids){
    global $mysqli, $file_id;
    if (isset($file_id)){
        mysqli_query($mysqli, 
            "INSERT INTO tasks (
            task_creator_id, 
            task_title, 
            task_body, 
            task_creation_date, 
            task_deadline_date, 
            task_last_updated, 
            groups_id, 
            reference_file_id
            )
            VALUES (
            $user_id,
            '$task_title',
            '$task_body',
            '$task_creation_date',
            '$task_deadline_date',
            '$task_creation_date',
            $group_id,
            $file_id
            );"
        );
    }
    else{
        mysqli_query($mysqli, 
            "INSERT INTO tasks (
            task_creator_id, 
            task_title, 
            task_body, 
            task_creation_date, 
            task_deadline_date, 
            task_last_updated, 
            groups_id
            )
            VALUES (
            $user_id,
            '$task_title',
            '$task_body',
            '$task_creation_date',
            '$task_deadline_date',
            '$task_creation_date',
            $group_id
            );"
        );
    }
    $task_id_query = mysqli_query($mysqli, "SELECT MAX(task_id) FROM tasks WHERE
        task_title='$task_title' AND
        groups_id=$group_id AND
        task_creator_id=$user_id AND
        task_creation_date='$task_creation_date'
        ;");
    $task_id_query = $task_id_query->fetch_assoc();
    $task_id = $task_id_query["MAX(task_id)"];

    // optimize this later
    $sent_tasks_count = 0;
    foreach ($sent_task_user_ids as $user_id) {
        if(is_numeric($user_id) && is_group_member($user_id, $group_id)){
            mysqli_query($mysqli, 
                "INSERT INTO sent_tasks (sent_task_status, task_id, user_id)
                VALUES (false, $task_id, $user_id);");
            $sent_tasks_count++;
        }
    }
    if($sent_tasks_count == 0){
        print_r($sent_task_user_ids);
        mysqli_query($mysqli, "DELETE FROM tasks WHERE task_id=$task_id;");
        if (isset($file_id)){
            mysqli_query($mysqli, "DELETE FROM files WHERE file_id=$file_id;");
            unlink(FILE_DIR . $file_id);
        }
    } 
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
    else{
        return true;
    }
}

function send_error_message($error_message){
    class JsonClass{}
    $json_object = new JsonClass();
    $json_object->errorMessage = $error_message;
    $json_object = json_encode($json_object);
    echo $json_object;
}

?>
