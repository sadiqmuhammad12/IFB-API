<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare volunteer object
$volunteer = new Donation($db);
  
// set ID property of record to read
$volunteer->cnic = isset($_GET['cnic']) ? $_GET['cnic'] : die();
  
// read the details of volunteer to be edited
$volunteer->read_single_volunteer();
  
if($volunteer->full_name!=null){
    // create array
    $volunteer_arr = array(
        "id" =>  $volunteer->id,
        "full_name" => $volunteer->full_name,
        "cnic" => $volunteer->cnic,
        "gender" => $volunteer->gender,
        "address" => $volunteer->address
  
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($volunteer_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user volunteer does not exist
    echo json_encode(array("message" => "Volunteer does not exist."));
}
?>