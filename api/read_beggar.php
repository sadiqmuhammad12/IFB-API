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
  

// query beggar
$stmt = $staff->read_beggar();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // beggar array
    $beggars_arr=array();
    $beggars_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $beggar_item=array(
            "id" => $id,
            "full_name" => $full_name,
            "cnic" =>$cnic,
            "gender" => $gender,
            "address" => $address,
            "added_by" => $added_by,
            "approved_by" => $approved_by
        );
  
        array_push($beggars_arr["records"], $beggar_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show donations data in json format
    echo json_encode($beggars_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no staff found
    echo json_encode(
        array("message" => "No beggar found.")
    );
}
