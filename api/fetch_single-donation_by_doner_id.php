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
// $donation->doner_id = isset($_GET['doner_id']) ? $_GET['doner_id'] : die();
// $doner_id = isset($_GET['doner_id']) ? $_GET['doner_id'] : "";
$doner_id=isset($_GET["doner_id"]) ? $_GET["doner_id"] : "";
  
// read the details of donation to be edited
$stmt = $donation->read_single_donation_by_doner_id($doner_id);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num > 0)
{
     // products array
     $donation_arr=array();
     $donation_arr["records"]=array();
     while($row = $stmt->fetch(PDO::FETCH_ASSOC))
     {
        extract($row);
        // create array
      $donation_item = array(
        "id" =>  $id,
        "beggar_full_name" => $beggar_full_name,
        "doner_name" => $doner_name,
        "beggar_cnic" => $beggar_cnic,
        "amount" => $amount,
        "doner_id" => $doner_id,
        "phone_no" =>$phone_no,
        "gender" =>$gender,
        "address" =>$address,
        "description" =>$description,
        "name" => $name //For image
    );
    array_push($donation_arr["records"], $donation_item);
 }
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