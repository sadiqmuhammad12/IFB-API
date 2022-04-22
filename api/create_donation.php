<?php

header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");

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
$beggar_full_name = $_POST['beggar_full_name'];	
$fileName  =  $_FILES['sendimage']['name'];
$tempPath  =  $_FILES['sendimage']['tmp_name'];
$fileSize  =  $_FILES['sendimage']['size'];
		
if(empty($fileName) && empty($beggar_cnic) && empty($amount) && empty($doner_name) && empty($phone_no)
   && empty($doner_id) && empty($gender) && empty($address) && empty($description) && empty($beggar_full_name))
{
	$errorMSG = json_encode(array("message" => "please select image and also insert other attribute", "status" => false));	
	echo $errorMSG;
}
// elseif(empty($doner_id))
// {
// 	$errorMSG = json_encode(array("message" => "please insert doner_id", "status" => false));	
// 	echo $errorMSG;
// }
else
{
	$upload_path = '../Image_Upload/'; // set upload folder path 
	
	$fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); // get image extension
		
	// valid image extensions
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
					
	// allow valid image file formats
	if(in_array($fileExt, $valid_extensions))
	{				
		//check file not exist our upload folder path
		if(!file_exists($upload_path . $fileName))
		{
			// check file size '5MB'
			if($fileSize < 5000000){
				move_uploaded_file($tempPath, $upload_path . $fileName); // move file from system temporary path to our upload folder path 
			}
			else{		
				$errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));	
				echo $errorMSG;
			}
		}
		else
		{		
			$errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "status" => false));	
			echo $errorMSG;
		}
	}
	else
	{		
		$errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));	
		echo $errorMSG;		
	}
}
		
// if no error caused, continue ....
if(!isset($errorMSG))
{
	// $query = mysqli_query($conn,'INSERT into image (name_img) VALUES("'.$fileName.'")');
    // $query = 'INSERT into donation (img) VALUES("'.$fileName.'")';
	$insert_query = "INSERT INTO `donation`(`name`,`doner_id`,`beggar_cnic`,`amount`,`doner_name`,`phone_no`,
	`gender`,`address`,`description`,`beggar_full_name`) VALUES(:name,:doner_id,:beggar_cnic,:amount,:doner_name,:phone_no,:gender,:address,:description,:beggar_full_name)";
                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($fileName)), PDO::PARAM_STR);
                // $insert_stmt->bindValue(':doner_id', $doner_id, PDO::PARAM_STR);
				$insert_stmt->bindValue(':doner_id', htmlspecialchars(strip_tags($doner_id)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':beggar_cnic', htmlspecialchars(strip_tags($beggar_cnic)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':amount', htmlspecialchars(strip_tags($amount)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':doner_name', htmlspecialchars(strip_tags($doner_name)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':phone_no', htmlspecialchars(strip_tags($phone_no)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':gender', htmlspecialchars(strip_tags($gender)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':address', htmlspecialchars(strip_tags($address)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':description', htmlspecialchars(strip_tags($description)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':beggar_full_name', htmlspecialchars(strip_tags($beggar_full_name)), PDO::PARAM_STR);
                // $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

                $insert_stmt->execute();
			
	echo json_encode(array("message" => "Data Uploaded Successfully", "status" => true));	
}

?>