<?php

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/Database.php';

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER["REQUEST_METHOD"] !== "POST" && $_SERVER["REQUEST_METHOD"] !== "PUT") {
    http_response_code(405);
    echo json_encode(["message" => "Bad method!", "success" => false]);
    exit;
}

if (isset($data->name) && isset($data->price) && isset($data->id)) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("UPDATE subscription_types SET name = :name, price = :price WHERE id = :id");
    $stmt->bindParam("name", $data->name);
    $stmt->bindParam("price", $data->price);
    $stmt->bindParam("id", $data->id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "Subscription type successfully updated!", "success" => true]);
    } else {
        echo json_encode(["message" => "An error occurred while updating the new subscription type!", "success" => false]);
    }
} else {
    echo json_encode(["message" => "No data received!", "success" => false]);
}
