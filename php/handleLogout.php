<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    session_start();
    $response = array("logout" => true);
    echo json_encode($response);
    session_destroy();
}
?>
