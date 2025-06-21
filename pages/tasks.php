<?php
session_start();
if($_SESSION["logged_in"] != true){
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="d-flex">
      <?php include "../components/sidebar.php"; ?>
      <div id="mainContainer" class="d-flex flex-column">
        <?php include "../components/navbar.php"; ?>
        <?php include "../components/taskContent.php"; ?>
      </div>
    </div>
    <script src="../js/tasks.js"></script>
  </body>
</html>
