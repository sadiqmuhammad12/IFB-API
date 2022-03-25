<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
  include_once '../config/database.php';
// instantiate donation object
include_once '../objects/donation.php';
  
$database = new Database();
$db = $database->getConnection();
  
$donation = new Donation($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->beggar_cnic) &&
    !empty($data->doner_id) &&
    !empty($data->amount) &&
    !empty($data->doner_name) &&
    !empty($data->date_time) 
){
  
    // set donation property values
    $donation->beggar_cnic = $data->beggar_cnic;
    $donation->doner_id = $data->doner_id;
    $donation->amount = $data->amount;
    $donation->doner_name = $data->doner_name;
    // $donation->date_time = date('Y-m-d H:i:s');
    $donation->date_time = $data->date_time;

    // create the donation
    if($donation->create_donation()){
  //
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Donation was created."));
    }
  
    // if unable to create the donation, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create donation."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create donation. Data is incomplete."));
}
?>