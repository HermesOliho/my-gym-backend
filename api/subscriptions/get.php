<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../classes/Subscription.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Subscription
$subscription = new Subscription($db);

// Lire les abonnements
$stmt = $subscription->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $subscriptions_arr = array();
    $subscriptions_arr["subscriptions"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $subscription_item = array(
            "id" => $id,
            "user_id" => $user_id,
            "type" => $type,
            "price" => $price,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "status" => $status
        );
        array_push($subscriptions_arr["subscriptions"], $subscription_item);
    }
    http_response_code(200);
    echo json_encode($subscriptions_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No subscriptions found."));
}
