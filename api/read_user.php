<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// instantiate database and donation object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new Donation($db);
  

// query user
$stmt = $user->read_user();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // user array
    $users_arr=array();
    $users_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
     
        extract($row);
  
        $user_item=array(
            "username" => $username,
            "email" =>$email
        );
  
        array_push($users_arr["records"], $user_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show donations data in json format
    echo json_encode($users_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no user found
    echo json_encode(
        array("message" => "No user found.")
    );
}
