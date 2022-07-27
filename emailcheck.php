<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once './config/database.php';
include_once './objects/donation.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new Donation($db);

// $returnData = [];
  
// set email property of record to read
$user->email = isset($_GET['email']) ? $_GET['email'] : die();
// $fields = ['fields' => ['username', 'email', 'password']];
// $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);
  
// read the details of user to be edited
$user->read_single_users();
  
if($user->id!=null){
    // create array
    $user_arr = array(
        // "email" =>  $user->email,
        "id" => $user->id,
        "status" => 1,
    );
   
    //  "id":$user->id;
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($user_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user user does not exist
    echo json_encode(array("message" => "user does not exist on this email.","status"=>0));
}
?>