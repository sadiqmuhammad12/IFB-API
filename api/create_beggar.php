<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate beggar object
include_once '../objects/donation.php';
  
$database = new Database();
$db = $database->getConnection();
  
$beggar = new Donation($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->full_name) &&
    !empty($data->cnic) &&
    !empty($data->gender) &&
    !empty($data->address) &&
    !empty($data->phone_no) &&
    !empty($data->doner_name) &&
    !empty($data->description) &&
    !empty($data->donation_amount) &&
    !empty($data->img)
){
  
    // set beggar property values
    $beggar->full_name = $data->full_name;
    $beggar->cnic = $data->cnic;
    $beggar->gender = $data->gender;
    $beggar->address = $data->address;
    $beggar->phone_no = $data->phone_no;
    $beggar->doner_name = $data->doner_name;
    $beggar->description = $data->description;
    $beggar->donation_amount = $data->donation_amount;
    $beggar->img = $data->img;
  
    // create the beggar
    if($beggar->create_beggar()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Beggar was created."));
    }
  
    // if unable to create the beggar, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create beggar."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create beggar. Data is incomplete."));
}
?>