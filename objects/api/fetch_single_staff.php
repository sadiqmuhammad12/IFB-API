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
  
// prepare staff object
$staff = new Donation($db);
  
// set full_name property of record to read
$staff->full_name = isset($_GET['full_name']) ? $_GET['full_name'] : die();
  
// read the details of staff to be edited
$staff->read_single_staff();
  
if($staff->full_name!=null){
    // create array
    $staff_arr = array(
        "id" =>  $staff->id,
        "full_name" => $staff->full_name,
        "email" => $staff->email,
        "passwords" => $staff->passwords
  
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($staff_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user staff does not exist
    echo json_encode(array("message" => "Staff does not exist."));
}
?>