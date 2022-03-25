<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare beggar for doner object
$beggar = new Donation($db);
  
// get id of beggar to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of beggar to be edited
$beggar->id = $data->id;
  
// set beggar property values
$beggar->full_name = $data->full_name;
$beggar->cnic = $data->cnic;
$beggar->gender = $data->gender;
$beggar->address = $data->address;
$beggar->added_by = $data->added_by;
$beggar->approved_by = $data->approved_by;
  
// update the beggar
if($beggar->update_beggar()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Beggar was updated."));
}
//  if unable the beggar, tell the user
elseif($beggar-> id == null)
{
     // set response code - 404 Not found
     http_response_code(404);
  
     // tell the user
     echo json_encode(array("message" => "Beggar was not found."));
}
// if unable to update the beggar, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update beggar."));
}
?>