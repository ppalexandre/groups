<?php
require "sqlConfig.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $timestamp = $_GET["timestamp"];
    $user_id = $_SESSION["user_id"];

    if(isset($user_id, $timestamp)){
        send_available_tasks_info($user_id, $timestamp);
    }
}

function send_available_tasks_info($user_id, $timestamp){
    global $mysqli;
    $task_id_query = mysqli_query($mysqli, "SELECT task_id FROM sent_tasks WHERE user_id=$user_id;");

    class JsonClass{}
    $jsonObject = new JsonClass();

    while($task_id_row = $task_id_query->fetch_assoc()) {
        $task_id = $task_id_row["task_id"];

        $task_last_updated_query = mysqli_query($mysqli, "SELECT task_last_updated FROM tasks WHERE task_id=$task_id;");
        $task_last_updated_query = $task_last_updated_query->fetch_assoc();
        $task_last_updated = strtotime($task_last_updated_query["task_last_updated"]);

        if ($timestamp < $task_last_updated){
            $task_contents_query = mysqli_query($mysqli, "SELECT task_title, task_body, task_creation_date, task_deadline_date, reference_file_id FROM tasks WHERE task_id=$task_id;");
            $task_contents_query = $task_contents_query->fetch_assoc();
            $task_title = $task_contents_query["task_title"];
            $task_body = $task_contents_query["task_body"];
            $task_creation_date = $task_contents_query["task_creation_date"];
            $task_deadline_date = $task_contents_query["task_deadline_date"];

            $reference_file_id = $task_contents_query["reference_file_id"];
            if (!empty($reference_file_id)){
                $reference_file_status = true;
                $reference_file_query = mysqli_query($mysqli, "SELECT file_name, file_size, file_mime_type FROM files WHERE file_id=$reference_file_id;"); 
                $reference_file_query = $reference_file_query->fetch_assoc();
                $reference_file_name = $reference_file_query["file_name"];
                $reference_file_size = $reference_file_query["file_size"];
                $reference_file_mime_type = $reference_file_query["file_mime_type"];
            }
            else{
                $reference_file_status = false;
            }

            $sent_task_query = mysqli_query($mysqli, "SELECT sent_task_status, sent_task_timestamp FROM sent_tasks WHERE task_id=$task_id;");
            $sent_task_query = $sent_task_query->fetch_assoc();
            $sent_task_status = $sent_task_query["sent_task_status"];
            $sent_task_timestamp = $sent_task_query["sent_task_timestamp"];

            $task = array(
                "taskId" => $task_id,
                "taskTitle" => $task_title,
                "taskBody" => $task_body,
                "taskCreationDate" => $task_creation_date,
                "taskDeadlineDate" => $task_deadline_date,
                "referenceFileStatus" => $reference_file_status,
                "sentTaskStatus" => $sent_task_status,
                "sentTaskTimestamp" => $sent_task_timestamp
            );
            if($reference_file_status){
                $task["referenceFileName"] = $reference_file_name;
                $task["referenceFileSize"] = $reference_file_size;
                $task["referenceFileMimeType"] = $reference_file_mime_type;
            }
            $task_list[] = $task;

        }
    }
    if(!empty($task_list)){
        $jsonObject->taskList = $task_list;
    }
    $jsonObject = json_encode($jsonObject);
    echo $jsonObject;
}
