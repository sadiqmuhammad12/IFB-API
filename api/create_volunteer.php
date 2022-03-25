<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate volunteer object
include_once '../objects/donation.php';
  
$database = new Database();
$db = $database->getConnection();
  
$volunteer = new Donation($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->full_name) &&
    !empty($data->cnic) &&
    !empty($data->gender) &&
    !empty($data->address)
){
  
    // set volunteer property values
    $volunteer->full_name = $data->full_name;
    $volunteer->cnic = $data->cnic;
    $volunteer->gender = $data->gender;
    $volunteer->address = $data->address;
  
    // create the volunter
    if($volunteer->create_volunteer()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Volunteer was created."));
    }
  
    // if unable to create the volunteer, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create volunteer."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create volunteer. Data is incomplete."));
}
?>