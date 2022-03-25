<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare volunteer object
$volunteer = new Donation($db);
  
// get volunteer id
$data = json_decode(file_get_contents("php://input"));
  
// set volunteer id to be deleted
$volunteer->id = $data->id;
  
// delete the volunteer
if($volunteer->delete_volunteer()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Volunteer was deleted."));
}
  
// if unable to delete the volunteer
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete volunteer."));
}
?>