<?php

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/Database.php';


if ($_SERVER["REQUEST_METHOD"] !== "POST" && $_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["message" => "Bad method!", "success" => false]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("DELETE FROM subscription_types WHERE id = :id");
    $stmt->bindParam("id", $data->id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "Subscription type successfully deleted!", "success" => true]);
    } else {
        echo json_encode(["message" => "An error occurred while deleting the new subscription type!", "success" => false]);
    }
} else {
    echo json_encode(["message" => "No data received!", "success" => false]);
}
