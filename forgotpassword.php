<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Invalid Request Method. HTTP method should be PUT',
    ]);
    exit;
endif;

// include_once '../config/database.php';
include_once './config/database.php';
$data = new Database();
$conn = $data->getConnection();

$data = json_decode(file_get_contents("php://input"));
// $data->id = isset($_GET['id']) ? $_GET['id'] : die();

if (!isset($data->email)) {
    echo json_encode(['success' => 0, 'message' => 'Please provide the  email.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `users` WHERE email=:email";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindParam(':email', $data->email, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :

        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $password = isset($data->password) ? $data->password : $row['password'];
     

        $update_query = "UPDATE `users` SET password = :password
        WHERE email = :email";

        $update_stmt = $conn->prepare($update_query);

        $update_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $update_stmt->bindParam(':email', $data->email, PDO::PARAM_INT);


        if ($update_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'Password updated successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Password Not updated. Something is going wrong.'
        ]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'Invalid email. No users found by the email.']);
        exit;
    endif;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}