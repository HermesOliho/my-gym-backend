<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/database.php';
include_once '../../classes/Session.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Session
$session = new Session($db);

// Récupérer les données POST
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) &&
    !empty($data->coach_id) &&
    !empty($data->date_time) &&
    !empty($data->duration)
) {
    $session->name = $data->name;
    $session->coach_id = $data->coach_id;
    $session->date_time = $data->date_time;
    $session->duration = $data->duration;

    if ($session->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Session was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create session."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Data is incomplete."));
}
