<?php

header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");


// require 'vendor/autoload.php';
require '../vendor/autoload.php';
// include 'dbconfig.php'; // include database connection file
include '../config/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format
$doner_id = $_POST['doner_id'];	
$beggar_cnic = $_POST['beggar_cnic'];	
$amount = $_POST['amount'];	
$doner_name = $_POST['doner_name'];	
$phone_no = $_POST['phone_no'];	
$gender = $_POST['gender'];	
$address = $_POST['address'];	
$description = $_POST['description'];	
// $imageName  =  $_FILES['sendimage']['name'];
// $tempPath  =  $_FILES['sendimage']['tmp_name'];
// $fileSize  =  $_FILES['sendimage']['size'];
$image = $_POST['name'];

		
if(empty($image) && empty($beggar_cnic) && empty($amount) && empty($doner_name) && empty($phone_no)
   && empty($doner_id) && empty($gender) && empty($address) && empty($description))
{
	$errorMSG = json_encode(array("message" => "please select image and also insert other attribute", "status" => false));	
	echo $errorMSG;
}


		
// if no error caused, continue ....
if(!isset($errorMSG))
{
    //For testing
    $insert_query = "INSERT INTO `donation`(`name`,`doner_id`,`beggar_cnic`,`amount`,`doner_name`,`phone_no`,`gender`,`address`,`description`) VALUES(:name,:doner_id,:beggar_cnic,:amount,:doner_name,:phone_no,:gender,:address,:description)";
    $insert_stmt = $conn->prepare($insert_query);

    // DATA BINDING
    $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($image)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':doner_id', htmlspecialchars(strip_tags($doner_id)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':beggar_cnic', htmlspecialchars(strip_tags($beggar_cnic)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':amount', htmlspecialchars(strip_tags($amount)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':doner_name', htmlspecialchars(strip_tags($doner_name)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':phone_no', htmlspecialchars(strip_tags($phone_no)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':gender', htmlspecialchars(strip_tags($gender)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':address', htmlspecialchars(strip_tags($address)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':description', htmlspecialchars(strip_tags($description)), PDO::PARAM_STR);
    $insert_stmt->execute();
	echo json_encode(array("message" => "Data Uploaded Successfully", "status" => true));	
}

?>