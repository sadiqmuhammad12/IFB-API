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

include_once '../config/database.php';
$data = new Database();
$conn = $data->getConnection();

$data = json_decode(file_get_contents("php://input"));
// $data->id = isset($_GET['id']) ? $_GET['id'] : die();

if (!isset($data->id)) {
    echo json_encode(['success' => 0, 'message' => 'Please provide the  ID.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `donation` WHERE id=:id";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindParam(':id', $data->id, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :

        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $doner_id = isset($data->doner_id) ? $data->doner_id : $row['doner_id'];
        $beggar_cnic = isset($data->beggar_cnic) ? $data->beggar_cnic : $row['beggar_cnic'];
        $amount = isset($data->amount) ? $data->amount : $row['amount'];
        $doner_name = isset($data->doner_name) ? $data->doner_name : $row['doner_name'];
        $phone_no = isset($data->phone_no) ? $data->phone_no : $row['phone_no'];
        $gender = isset($data->gender) ? $data->gender : $row['gender'];
        $address = isset($data->address) ? $data->address : $row['address'];
        $description = isset($data->description) ? $data->description : $row['description'];

        $update_query = "UPDATE `donation` SET doner_id = :doner_id, beggar_cnic = :beggar_cnic, amount = :amount,
        doner_name = :doner_name, phone_no = :phone_no, gender = :gender, address = :address, description = :description
        WHERE id = :id";

        $update_stmt = $conn->prepare($update_query);

        $update_stmt->bindValue(':doner_id', htmlspecialchars(strip_tags($doner_id)), PDO::PARAM_STR);
        $update_stmt->bindValue(':beggar_cnic', htmlspecialchars(strip_tags($beggar_cnic)), PDO::PARAM_STR);
        $update_stmt->bindValue(':amount', htmlspecialchars(strip_tags($amount)), PDO::PARAM_STR);
        $update_stmt->bindValue(':doner_name', htmlspecialchars(strip_tags($doner_name)), PDO::PARAM_STR);
        $update_stmt->bindValue(':phone_no', htmlspecialchars(strip_tags($phone_no)), PDO::PARAM_STR);
        $update_stmt->bindValue(':gender', htmlspecialchars(strip_tags($gender)), PDO::PARAM_STR);
        $update_stmt->bindValue(':address', htmlspecialchars(strip_tags($address)), PDO::PARAM_STR);
        $update_stmt->bindValue(':description', htmlspecialchars(strip_tags($description)), PDO::PARAM_STR);
        $update_stmt->bindParam(':id', $data->id, PDO::PARAM_INT);


        if ($update_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'Donation updated successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Donation Not updated. Something is going wrong.'
        ]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'Invalid ID. No donations found by the ID.']);
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