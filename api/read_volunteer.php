<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// instantiate database and donation object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$volunteer = new Donation($db);
  

// query volunteer
$stmt = $volunteer->read_volunteer();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // volunteer array
    $volunteers_arr=array();
    $volunteers_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
  
        $volunteer_item=array(
            "id" => $id,
            "full_name" => $full_name,
            "cnic" =>$cnic,
            "gender" => $gender,
            "address" => $address
        );
  
        array_push($volunteers_arr["records"], $volunteer_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show donations data in json format
    echo json_encode($volunteers_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no volunteer found
    echo json_encode(
        array("message" => "No volunteer found.")
    );
}
