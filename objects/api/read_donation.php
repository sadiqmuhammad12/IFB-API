<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/donation.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$donation = new Donation($db);
  

// query donation
$stmt = $donation->read_donation();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // donation array
    $donations_arr=array();
    $donations_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $donation_item=array(
            "id" => $id,
            "beggar_cnic" =>$beggar_cnic,
            "beggar_full_name" =>$beggar_full_name,
            "doner_name" => $doner_name,
            "amount" => $amount
        );
  
        array_push($donations_arr["records"], $donation_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show donations data in json format
    echo json_encode($donations_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no donation found
    echo json_encode(
        array("message" => "No donation found.")
    );
}
