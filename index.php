<?php







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
$products = array(); 
 
//this is our sql query 
$sql = "SELECT id, name, description, image_url FROM product;";
 
//creating an statment with the query
$stmt = $conn->prepare($sql);
 
//executing that statment
$stmt->execute();
 
//binding results for that statment 
$stmt->bind_result($id, $name,$description,$image_url);
 
//looping through all the records
while($stmt->fetch()){
 
 //pushing fetched data in an array 
 $temp = [
 'id'=>$id,
 'name'=>$name,
 'description'=>$description,
 'image_url'=>$image_url
 ];
 
 //pushing the array inside the hero array 
 array_push($products, $temp);
}
 
//displaying the data in json format 
echo json_encode($products);






















// /*
//  * Following code will list all the emp
//  */

// // array for JSON response
// $response = array();

// $mysqli = new mysqli("localhost","root","","usersdb");
//     // include db connect class
//     require_once 'include/DB_Connect.php';

//     // connecting to db
//     $db = new DB_CONNECT();
    
// // get all emp from emp table
// $result = $mysqli -> query("SELECT * FROM product") ;

// // check for empty result
// if (mysqli_num_rows($result) > 0) {


//     // looping through all results
//     // emp node
//     $response["productlist"] = array();

//     while ($row = mysqli_fetch_array($result)) {
//          $response["success"] = 1;
//         // temp user array
//             $productlist = array();
//             $productlist["id"] = $row["id"];
//             $productlist["name"] = $row["name"];
//             $productlist["description"] = $row["description"];
//             $productlist["image_url"] = $row["image_url"];



//         // push single product into final response array
//         array_push($response["productlist"], $productlist);
//     }
//     // success

//      $response["message"] = "DISPLAYED Success";

//     // echoing JSON response
//     echo json_encode($response);
// } else {
//     // no emp found
//     $response["success"] = 0;
//     $response["message"] = "No User found";

//     // echo no users JSON
//     echo json_encode($response);
// }









// // json response array
// $response = array("error" => FALSE);
 


//     // get the user by email and password
//     $product = $db->getAllProducts();
 
//     if ($product != false) {
//         // use is found
//         $response["error"] = FALSE;
//         $response= $product;
//         echo json_encode($response);
//     } else {
//         // user is not found with the credentials
//         $response["error"] = TRUE;
//         $response["error_msg"] = "Login credentials are wrong. Please try again!";
//         echo json_encode($response);
//     }
//  else {
//     // required post params is missing
//     $response["error"] = TRUE;
//     $response["error_msg"] = "Required parameters email or password is missing!";
//     echo json_encode($response);
//  }
?>