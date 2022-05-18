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
$qoute = $_POST['qoute'];	
$imageName  =  $_FILES['sendimage']['name'];
$tempPath  =  $_FILES['sendimage']['tmp_name'];
$fileSize  =  $_FILES['sendimage']['size'];

		
if(empty($imageName) && empty($qoute))
{
	$errorMSG = json_encode(array("message" => "please select image and also insert other attribute", "status" => false));	
	echo $errorMSG;
}

else
{
	$upload_path = '../slogan_image/'; // set upload folder path 
	
	$fileExt = strtolower(pathinfo($imageName,PATHINFO_EXTENSION)); // get image extension
		
	// valid image extensions
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
					
	// allow valid image file formats
	if(in_array($fileExt, $valid_extensions))
	{				
		//check file not exist our upload folder path
		if(!file_exists($upload_path . $imageName))
		{
			// check file size '5MB'
			if($fileSize < 5000000){
				move_uploaded_file($tempPath, $upload_path . $imageName); // move file from system temporary path to our upload folder path 
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
    //For testing
	$insert_query = "INSERT INTO `slogan`(`name`,`qoute`) VALUES(:name,:qoute)";
                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($imageName)), PDO::PARAM_STR);
				$insert_stmt->bindValue(':qoute', htmlspecialchars(strip_tags($qoute)), PDO::PARAM_STR);
                $insert_stmt->execute();
			
	echo json_encode(array("message" => "Data Uploaded Successfully", "status" => true));	
}

?>