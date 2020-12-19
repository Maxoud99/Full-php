<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['user_idd']) && isset($_POST['linkmaster_id']) )  {
     // receiving the post params
    $user_idd = (int)$_POST['user_idd'];
    $linkmaster_id = (int)$_POST['linkmaster_id'];
        // create a new user
        
        $saved_items_locations = $db->storesaved($user_idd, $linkmaster_id);
        if ($saved_items_locations) {
            // user stored successfully
            $response["success"] = 1;
            $response["message"] = "inserted successfully!";
            // $response["saved_items_locations"]["user_idd"] = $saved_items_locations["user_idd"];
            // $response["saved_items_locations"]["linkmaster_id"] = $saved_items_locations["linkmaster_id"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = $saved_items_locations;
            echo json_encode($response);
        }
    
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>