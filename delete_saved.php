<?php
require_once 'include/DB_Functions.php';
   $db = new DB_Functions();




   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "usersdb";

    $link =new mysqli($servername, $username, $password);
    mysqli_select_db($link,$dbname);
    $linkmaster_id=$_POST['linkmaster_id'];
    $user_idd=$_POST['user_idd'];
    $result = mysqli_query($link,"DELETE FROM saved_items_locations WHERE linkmaster_id=$linkmaster_id and user_idd=$user_idd");

    $response["success"] = 1;
    $response["message"] = "Deleted successfully!";
    echo json_encode($response); 
?>