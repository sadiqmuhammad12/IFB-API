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
$staff = new Donation($db);
  

// query staff
$stmt = $staff->read_staff();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // staff array
    $staffs_arr=array();
    $staffs_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
     
        extract($row);
  
        $staff_item=array(
            "id" => $id,
            "full_name" => $full_name,
            "email" =>$email,
            "passwords" => $passwords
        );
  
        array_push($staffs_arr["records"], $staff_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show donations data in json format
    echo json_encode($staffs_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no staff found
    echo json_encode(
        array("message" => "No staff found.")
    );
}
