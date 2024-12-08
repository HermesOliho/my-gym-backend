<?php

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/Database.php';

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["message" => "Bad method!", "success" => false]);
    exit;
}

if (isset($data->name) && isset($data->price)) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("INSERT INTO subscription_types(name, price) VALUES(:name, :price)");
    $stmt->bindParam("name", $data->name);
    $stmt->bindParam("price", $data->price);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "Subscription type successfully added!", "success" => true]);
    } else {
        echo json_encode(["message" => "An error occurred while creating the new subscription type!", "success" => false]);
    }
} else {
    echo json_encode(["message" => "No data received!", "success" => false]);
}
