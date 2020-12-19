<?php
 

class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password, $address, $phoneNumber) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
 
        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, address, phoneNumber, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, ?, ?,NOW())");
        $stmt->bind_param("sssssss", $uuid, $name, $email, $address, $phoneNumber, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
    public function storesaved($user_idd, $linkmaster_id) {

        $stmt = $this->conn->prepare("INSERT INTO saved_items_locations(user_idd, linkmaster_id) VALUES(?, ?)");
        $stmt->bind_param("ss", $user_idd, $linkmaster_id);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
     /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }
    /**
     * Get shop by id
     */
    public function getShopById($id) {
 
        $stmt = $this->conn->prepare("SELECT * FROM shop WHERE id = ?");
 
        $stmt->bind_param("s", $id);
 
        if ($stmt->execute()) {
            $shop = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
           return $shop;
        } else {
            return NULL;
        }
    }
    /**
     * Get shop_id by product_id
     */
    public function getShopIdbyproductId($product_id,$id) {
 
        $stmt = $this->conn->prepare("SELECT shop_id FROM linkmaster where product_id=? and id=?");
       
        $stmt->bind_param("ss", $product_id,$id);
 
        if ($stmt->execute()) {
            $shop_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
           return $shop_id;
        } else {
            return NULL;
        }
    }
    /**
     * Get product by id
     */
    public function getProductById($id) {
 
        $stmt = $this->conn->prepare("SELECT * FROM product WHERE id = ?");
 
        $stmt->bind_param("s", $id);
 
        if ($stmt->execute()) {
            $product = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
           return $product;
        } else {
            return NULL;
        }
    }
  
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
 

      
    /***********************************************
     * Get products
     */
    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM product");
        $stmt->execute();
        $products = $stmt->get_result();
        $stmt->close();
        return $products;
    }

    /*********************** get linkmaster ************************ */

    public function getLink($linkmaster_id){
     
        $productsDetails = array(); 
 
        $stmt = $this->conn->prepare("SELECT * FROM linkmaster WHERE id = ?");
 
        $stmt->bind_param("s", $linkmaster_id);
 
    if ($stmt->execute()) {
         $link = $stmt->get_result()->fetch_assoc();
        $stmt->close();
     if($linkmaster_id==$link["id"]){
    //  $relative=$this->getShopIdbyproductId($product_id,$id);
     $shop =$this->getShopById($link["shop_id"]);
     $product =$this->getProductById($link["product_id"]);
      //pushing fetched data in an array 
     $temp = [
     'id'=>$link["id"],
      'ShopName'=>$shop["name"],
      'ShopLat'=>$shop["latitude"],
     'Shoplong'=>$shop["longitude"],
     'ProductName'=>$product["name"],
     'ProductDesc'=>$product["description"],

      'price'=>$link["price"],
     'available_Special_offers'=>$link["available_Special_offers"]
     ];
 
     //pushing the array inside the hero array 
      array_push($productsDetails, $temp);
      return $productsDetails;
     }
     

      //displaying the data in json format 
      
      
      else {
      // required post params is missing
      $response["error"] = TRUE;
      $response["error_msg"] = "Required parameters killId is missing!";
      return false;

      }}
}
}

?>