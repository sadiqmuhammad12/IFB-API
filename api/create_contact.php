<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate contact object
include_once '../objects/donation.php';
  
$database = new Database();
$db = $database->getConnection();
  
$contact = new Donation($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->full_name) &&
    !empty($data->email) &&
    !empty($data->address) &&

    !empty($data->comments)
){
  
    // set contact property values
    $contact->full_name = $data->full_name;
    $contact->email = $data->email;
    $contact->address = $data->address;
    $contact->comments = $data->comments;
  
    // create the contact
    if($contact->create_contact()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Contact was created."));
    }
  
    // if unable to create the contact, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create contact."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create contact. Data is incomplete."));
}
?>