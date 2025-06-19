<?php
require "sqlConfig.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $task_id = $_GET["taskId"];
    $user_id = $_SESSION["user_id"];

    if(isset($task_id, $user_id) && is_numeric($task_id)){
        $sent_task_id = query_sent_task_id($task_id, $user_id); // used for authentication
        if (!empty($sent_task_id)){
            send_reference_file($task_id);
        }
    }
}

function send_reference_file($task_id){
    global $mysqli;
    $reference_file_id_query = mysqli_query($mysqli, "SELECT reference_file_id FROM tasks WHERE task_id=$task_id;");
    $reference_file_id = $reference_file_id_query->fetch_assoc();
    $reference_file_id = $reference_file_id["reference_file_id"];

    $file_dir = "../uploads/";
    $file = $file_dir . $reference_file_id;
    echo $file;

    header('Content-type: application/octet-stream');
    header("Content-Type: " . mime_content_type($file));
    header("Content-Disposition: attachment; filename=" . $file);

    while (ob_get_level()) {
        ob_end_clean();
    }
    readfile($file);
}

function query_sent_task_id($task_id, $user_id){
    global $mysqli;
    // doubles as authentication
    $sent_task_id_query = mysqli_query($mysqli, "SELECT sent_task_id FROM sent_tasks WHERE task_id=$task_id AND user_id=$user_id;");
    $sent_task_id = $sent_task_id_query->fetch_assoc();
    $sent_task_id = $sent_task_id["sent_task_id"];
    return $sent_task_id;
}


?>
