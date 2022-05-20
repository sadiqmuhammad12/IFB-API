<?php
header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");

// include 'dbconfig.php'; // include database connection file
include '../config/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

$data = json_decode(file_get_contents("php://input"), true);
  $id = $data['id'];
  $stmt_edit = $conn->prepare('SELECT  *FROM slogan WHERE id =:id');
  $stmt_edit->execute(array(':id'=>$id));
  $edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
  extract($edit_row);
 

 
 
  $qoute = $_POST['qoute'];// user name   
  $imgFile = $_FILES['user_image']['name'];
  $tmp_dir = $_FILES['user_image']['tmp_name'];
  $imgSize = $_FILES['user_image']['size'];
     
  if($imgFile)
  {
   $upload_dir = '../slogan_image/'; // upload directory 
   $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
   $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
   $name = rand(1000,1000000).".".$imgExt;
   if(in_array($imgExt, $valid_extensions))
   {   
    if($imgSize < 5000000)
    {
     unlink($upload_dir.$edit_row['name']);
     move_uploaded_file($tmp_dir,$upload_dir.$name);
    }
    else
    {
     $errMSG = "Sorry, your file is too large it should be less then 5MB";
    }
   }
   else
   {
    $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";  
   } 
  }
  else
  {
   // if no image selected the old image remain as it is.
   $name = $edit_row['name']; // old image from database
  } 
      
  
  // if no error occured, continue ....
  if(!isset($errMSG))
  {
   $stmt = $conn->prepare('UPDATE slogan 
              SET qoute=:qoute,  
               name=:name 
               WHERE id=:id');
   $stmt->bindParam(':qoute',$qoute);
   $stmt->bindParam(':name',$name);
   $stmt->bindParam(':id',$id);
    
   if($stmt->execute()){
	$errMSG = "Successfully Updated ...";
   }
   else{
    $errMSG = "Sorry Data Could Not Updated !";
   }
  }    

?>