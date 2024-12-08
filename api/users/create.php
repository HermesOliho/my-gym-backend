<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../../config/Database.php';
require_once '../../classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

// echo file_get_contents("php://input");die;
if (!empty($data->name) && !empty($data->contact)) {
    $user->name = $data->name;
    $user->contact = strtolower($data->contact);
    $user->subscription_type = $data->subscription;
    $user->start_date = $data->start_date;
    $user->end_date = $data->end_date;

    try {
        if ($user->create()) {
            http_response_code(201);
            echo json_encode(array("success" => true, "message" => "User was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("success" => false, "message" => "Unable to create user."));
        }
    } catch (Exception $e) {
        http_response_code(503);
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Data is incomplete."));
}
