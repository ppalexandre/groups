<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    session_start();
    $user_name = $_SESSION["user_name"];
    $response = array("userName" => $user_name);
    echo json_encode($response);
}
?>
