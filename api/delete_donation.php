<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Invalid Request Method. HTTP method should be DELETE',
    ]);
    exit;
endif;

// require 'database.php';
include_once '../config/database.php';
$database = new Database();
$conn = $database->getConnection();

// $data = json_decode(file_get_contents("php://input"));
$database->id = isset($_GET['id']) ? $_GET['id'] : die();

if (!isset($database->id)) {
    echo json_encode(['success' => 0, 'message' => 'Please provide the  ID.']);
    exit;
}

try {

    $fetch_donation = "SELECT * FROM `donation` WHERE id=:id";
    $fetch_stmt = $conn->prepare($fetch_donation);
    $fetch_stmt->bindParam(':id', $database->id, PDO::PARAM_INT);
    // $fetch_stmt->bindParam(1, $database->id);

    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :

        $delete_donation = "DELETE FROM `donation` WHERE id=:id";
        $delete_donation_stmt = $conn->prepare($delete_donation);
        $delete_donation_stmt->bindParam(':id', $database->id,PDO::PARAM_INT);
        // $delete_donation_stmt->bindParam(1, $database->id);

        if ($delete_donation_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'Donation Deleted successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Donation Not Deleted. Something is going wrong.'
        ]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'Invalid ID. No donation found by the ID.']);
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