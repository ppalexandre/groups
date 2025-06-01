<?php
require "sqlConfig.php";
session_start();
    
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    handle_file_upload();
}

function handle_file_upload(){
    global $mysqli;
    if (isset($_FILES["file"])){
        $file_dir = "../uploads/";
        $file_name = mysqli_real_escape_string($mysqli, basename($_FILES["file"]["name"]));
        $file_tmp_name = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_mime_type = mime_content_type($file_tmp_name);

        // ~5MB size limit
        if ($file_size > 5000000) {
            echo "Error: File failed to upload, file size is too big"; 
            return false;
        }

        store_file_information($file_name, $file_size, $file_mime_type);
        $file_id_query = mysqli_query($mysqli, "SELECT MAX(file_id) FROM files;");
        $file_id = $file_id_query->fetch_assoc();
        $file_id = $file_id["MAX(file_id)"];
        $file_full_path = $file_dir . $file_id;

        if(is_file($file_full_path)) {
            echo "Error: File path already exists";
            return false;
        }

        if(move_uploaded_file($file_tmp_name, $file_full_path)){
            echo "File sucessfully uploaded";
            return true;
        }

        else{
            mysqli_query($mysqli, "DELETE FROM files WHERE file_id='$file_id';");
            echo "Error: Failed to upload";
            return false;
        }

    }
}

function store_file_information($file_name, $file_size, $file_mime_type){
    global $mysqli;
    $insert_file = mysqli_query($mysqli, 
        "INSERT INTO files (file_name, file_size, file_mime_type)
        VALUES('$file_name', '$file_size', '$file_mime_type');");
}
?>
