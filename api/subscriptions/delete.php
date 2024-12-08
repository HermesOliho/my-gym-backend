<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once '../../config/database.php';
include_once '../../classes/Subscription.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Subscription
$subscription = new Subscription($db);

// Récupérer les données DELETE
$data = json_decode(file_get_contents("php://input"));

// S'assurer que l'ID est fourni
if (!empty($data->id)) {
    $subscription->id = $data->id;

    if ($subscription->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Subscription was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete subscription."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing ID."));
}
