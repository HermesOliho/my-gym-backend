<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../classes/Payment.php';

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$stmt = $payment->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $payments_arr = array();
    $payments_arr["payments"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $payment_item = array(
            "id" => $id,
            "member_id" => $member_id,
            "subscription_id" => $subscription_id,
            "amount" => $amount,
            "payment_date" => $payment_date,
            "payment_method" => $payment_method
        );

        array_push($payments_arr["payments"], $payment_item);
    }

    http_response_code(200);
    echo json_encode($payments_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No payments found."));
}
