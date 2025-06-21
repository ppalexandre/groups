<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Groups</title>
  </head>
  <body>
    <?php
      session_start();
      if($_SESSION["logged_in"] != true){
        header("Location: pages/login.php"); 
        exit();
      }
      else{
        header("Location: pages/tasks.php"); 
        exit();
      }
    ?>
  </body>
</html>
