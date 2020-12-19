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
$sql = "SELECT id, shop_id, product_id, price, available_Special_offers FROM linkmaster";
 
//creating an statment with the query
$stmt = $conn->prepare($sql);
 
//executing that statment
$stmt->execute();
 
//binding results for that statment 
$stmt->bind_result($id, $shop_id,$product_id,$price,$available_Special_offers);
if(isset($_POST['killId'])){
$killId = $_POST['killId'];
//looping through all the records
while($stmt->fetch()){
    
    
    if($killId==$product_id){
    $relative=$db->getShopIdbyproductId($product_id,$id);
    $shop = $db->getShopById($relative["shop_id"]);
    $product = $db->getProductById($product_id);
 //pushing fetched data in an array 
  $temp = [
  'id'=>$id,
  'ShopName'=>$shop["name"],
  'ShopLat'=>$shop["latitude"],
  'Shoplong'=>$shop["longitude"],
  'ProductName'=>$product["name"],
  'ProductDesc'=>$product["description"],

  'price'=>$price,
  'available_Special_offers'=>$available_Special_offers
  ];
 
 //pushing the array inside the hero array 
 array_push($productsDetails, $temp);
}
}

//displaying the data in json format 
echo json_encode($productsDetails);
}
else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters killId is missing!";
    echo json_encode($response);
}



  
?>