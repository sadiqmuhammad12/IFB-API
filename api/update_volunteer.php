<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare volunteer for doner object
$volunteer = new Donation($db);
  
// get cnic of volunteer to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of volunteer to be edited
$volunteer->cnic = $data->cnic;
  
// set volunteer property values
$volunteer->full_name = $data->full_name;
// $volunteer->id = $data->id;
$volunteer->gender = $data->gender;
$volunteer->address = $data->address;
  
// update the volunteer
if($volunteer->update_volunteer()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Volunteer was updated."));
}
//  if unable the volunteer, tell the user
elseif($volunteer-> cnic == null)
{
     // set response code - 404 Not found
     http_response_code(404);
  
     // tell the user
     echo json_encode(array("message" => "Volunteer was not found."));
}

// if unable to update the volunteer, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update volunteer."));
}
?>