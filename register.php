<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// require __DIR__ . '/config/Database.php';
// require __DIR__.'/config/database.php';
include_once './config/database.php';
// include_once './config/database.php';
$db_connection = new Database();
$conn = $db_connection->getConnection();

function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message,
    
    ], $extra);
}

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($data->username)
    || !isset($data->email)
    || !isset($data->password)
    || empty(trim($data->username))
    || empty(trim($data->email))
    || empty(trim($data->password))
) :

    $fields = ['fields' => ['username', 'email', 'password']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :

    $username = trim($data->username);
    $email = trim($data->email);
    $password = trim($data->password);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
        $returnData = msg(0, 422, 'Invalid Email Address!');

    elseif (strlen($password) < 8) :
        $returnData = msg(0, 422, 'Your password must be at least 8 characters long!');

    elseif (strlen($username) < 3) :
        $returnData = msg(0, 422, 'Your name must be at least 3 characters long!');

    else :
        try {

            $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
            $check_email_stmt = $conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $returnData = msg(0, 422, 'This E-mail already in use!');

            else :
                $insert_query = "INSERT INTO `users`(`username`,`email`,`password`) VALUES(:username,:email,:password)";
                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':username', htmlspecialchars(strip_tags($username)), PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

                $insert_stmt->execute();
                

                // This code for email sending
                $to = $email;
                $subject = "For Verification";
                $random_code = rand(10000,50000); // This is for code generation
                $message = "This is Your Code ".$random_code; 
                $headers = "From: sender\'s email";
              
                mail($to, $subject, $message, $headers); // Send our email
                
                
                // $returnData = msg(1, 201, 'You have successfully registered.');
                $returnData = [
                    'code' => $random_code,
                    'success' => 1,
                    'status' => 201,
                    'message' => 'You have successfully registered.'
                ];
            endif;
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;
endif;

echo json_encode($returnData);
