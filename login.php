<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// require __DIR__.'/config/database.php';
include_once './config/database.php';
include_once './config/JwtHandler.php';
function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

$db_connection = new Database();
$conn = $db_connection->getConnection();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = msg(0,404,'Page Not Found!');

// CHECKING EMPTY FIELDS
elseif(!isset($data->email) 
    || !isset($data->password)
    || empty(trim($data->email))
    || empty(trim($data->password))
    ):

    $fields = ['fields' => ['email','password']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:
    $email = trim($data->email);
    $password = trim($data->password);

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $returnData = msg(0,422,'Invalid Email Address!');
    
    // IF PASSWORD IS LESS THAN 8 THE SHOW THE ERROR
    elseif(strlen($password) < 8):
        $returnData = msg(0,422,'Your password must be at least 8 characters long!');

    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else:
        try{
            
            $fetch_user_by_email = "SELECT * FROM `users` WHERE `email`=:email";
            
            $query_stmt = $conn->prepare($fetch_user_by_email);
            
            $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
            
            $query_stmt->execute();
            
            
            // For Read id
            // $user = $query_stmt->fetch(PDO::FETCH_OBJ);
            // if($query_stmt->rowCount())
            // {
            //     $_SESSION['id'] = $user->id;
            //     echo $_SESSION['id'];
            //     // header('./api/create_donation.php');
            //     header('./objects/donation.php');
            // }
            // else{return false;}

            // IF THE USER IS FOUNDED BY EMAIL
            if($query_stmt->rowCount()):
                $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                // $user = $query_stmt->fetch(PDO::FETCH_OBJ);
                    
                $check_password = password_verify($password, $row['password']);
                // $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_id'] = $row['id'];
                echo "New User ID  is :  ". $_SESSION['user_id'];
                // echo "Id is : " . $row['id'];
                header('./objects/donation.php');
                // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                // IF PASSWORD IS CORRECT THEN SEND THE LOGIN 
                if($check_password):
                    $jwt = new JwtHandler();
                    $token = $jwt->jwtEncodeData(
                        'http://localhost/identifyfakebeggar/',
                        array("user_id"=> $row['id'])
                    );
                    $returnData = [
                        'id' => $row['id'],
                        'success' => 1,
                        'message' => 'You have successfully logged in.',
                        'token' => $token
                    ];
                     
                // IF INVALID PASSWORD
                else:
                    $returnData = msg(0,422,'Invalid Password!');
                    
                    
                endif;
                
                

            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else:
                $returnData = msg(0,422,'Invalid Email Address!');
            endif;
            
            
        }
        
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }

    endif;

endif;

echo json_encode($returnData);