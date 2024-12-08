<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/database.php';
include_once '../../classes/Subscription.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Subscription
$subscription = new Subscription($db);

// Récupérer les données POST
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->user_id) &&
    !empty($data->type) &&
    !empty($data->price) &&
    !empty($data->start_date) &&
    !empty($data->end_date)
) {
    $subscription->user_id = $data->user_id;
    $subscription->type = $data->type;
    $subscription->price = $data->price;
    $subscription->start_date = $data->start_date;
    $subscription->end_date = $data->end_date;
    $subscription->status = isset($data->status) ? $data->status : 'active';

    if ($subscription->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Subscription was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create subscription."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Data is incomplete."));
}
