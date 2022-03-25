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
  
// prepare beggar object
$beggar = new Donation($db);
  
// set ID property of record to read
$beggar->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of beggar to be edited
$beggar->read_single_beggar();
  
if($beggar->full_name!=null){
    // create array
    $beggar_arr = array(
        "id" =>  $beggar->id,
        "full_name" => $beggar->full_name,
        "cnic" => $beggar->cnic,
        "gender" => $beggar->gender,
        "address" => $beggar->address,
        "added_by" => $beggar->added_by,
        "approved_by" => $beggar->approved_by
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($beggar_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user beggar does not exist
    echo json_encode(array("message" => "Beggar does not exist."));
}
?>