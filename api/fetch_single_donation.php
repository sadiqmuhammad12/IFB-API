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
  
// prepare donation object
$donation = new Donation($db);
  
// set ID property of record to read
$donation->beggar_cnic = isset($_GET['beggar_cnic']) ? $_GET['beggar_cnic'] : die();
  
// read the details of donation to be edited
$donation->read_single_donation();
  
if($donation->doner_name!=null){
    // create array
    $donation_arr = array(
        "id" =>  $donation->id,
        "beggar_full_name" => $donation->beggar_full_name,
        "doner_name" => $donation->doner_name,
        "beggar_cnic" => $donation->beggar_cnic,
        "amount" => $donation->amount,
        "date_time" => $donation->date_time
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($donation_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user donation does not exist
    echo json_encode(array("message" => "donation does not exist."));
}
?>