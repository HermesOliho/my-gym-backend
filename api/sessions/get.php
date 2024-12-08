<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../classes/Session.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet Session
$session = new Session($db);

// Lire les séances
$stmt = $session->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $sessions_arr = array();
    $sessions_arr["sessions"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $session_item = array(
            "id" => $id,
            "name" => $name,
            "coach_id" => $coach_id,
            "date_time" => $date_time,
            "duration" => $duration
        );
        array_push($sessions_arr["sessions"], $session_item);
    }
    http_response_code(200);
    echo json_encode($sessions_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No sessions found."));
}
