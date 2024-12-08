<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

include_once '../../config/database.php';
include_once '../../classes/Subscription.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Subscription
$subscription = new Subscription($db);

// Récupérer les données PUT
$data = json_decode(file_get_contents("php://input"));

// S'assurer que l'ID est fourni
if (!empty($data->id)) {
    $subscription->id = $data->id;

    // Définir les nouvelles valeurs
    $subscription->user_id = $data->user_id;
    $subscription->type = $data->type;
    $subscription->price = $data->price;
    $subscription->start_date = $data->start_date;
    $subscription->end_date = $data->end_date;
    $subscription->status = $data->status;

    if ($subscription->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Subscription was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update subscription."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing ID."));
}
