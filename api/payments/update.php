<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

include_once '../../config/database.php';
include_once '../../classes/Payment.php';

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $payment->id = $data->id;

    $payment->member_id = $data->member_id;
    $payment->subscription_id = $data->subscription_id;
    $payment->amount = $data->amount;
    $payment->payment_date = $data->payment_date;
    $payment->payment_method = $data->payment_method;

    if ($payment->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Payment was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update payment."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing payment ID."));
}
