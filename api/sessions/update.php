<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

include_once '../../config/database.php';
include_once '../../classes/Session.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Session
$session = new Session($db);

// Récupérer les données PUT
$data = json_decode(file_get_contents("php://input"));

// S'assurer que l'ID est fourni
if (!empty($data->id)) {
    $session->id = $data->id;

    // Définir les nouvelles valeurs
    $session->name = $data->name;
    $session->coach_id = $data->coach_id;
    $session->date_time = $data->date_time;
    $session->duration = $data->duration;

    if ($session->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Session was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update session."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing ID."));
}
