<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();




$servername = "localhost";
$username = "root";
$password = "";
$database = "usersdb";
 
 
//creating a new connection object using mysqli 
$conn = new mysqli($servername, $username, $password, $database);
 
//if there is some error connecting to the database
//with die we will stop the further execution by displaying a message causing the error 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
//if everything is fine
 
//creating an array for storing the data 
$productsDetails = array(); 
 
//this is our sql query 
$sql = "SELECT id, user_idd, linkmaster_id FROM saved_items_locations";
 
//creating an statment with the query
$stmt = $conn->prepare($sql);
 
//executing that statment
$stmt->execute();
 
//binding results for that statment 
$stmt->bind_result($id, $user_idd,$linkmaster_id);
if(isset($_POST['userId'])){
$userId = $_POST['userId'];
//looping through all the records

while($stmt->fetch()){
    
    
    if($userId==$user_idd){
    $data=$db->getLink($linkmaster_id);
 //pushing fetched data in an array 
  
 echo json_encode($data);
 //pushing the array inside the hero array 
 
}
}

//displaying the data in json format 

}
else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters userid is missing!";
    echo json_encode($response);
}



  
?>