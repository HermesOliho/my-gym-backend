<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/Database.php';
require_once '../../classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$stmt = isset($_GET["id"]) ? $user->readOne(intval($_GET["id"])) : $user->read();
$num = $stmt->rowCount();

if ($num > 0) {
    if (isset($_GET["id"])) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($user);
    } else {
        $users_arr = array();
        $users_arr["users"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($users_arr["users"], $row);
        }
        http_response_code(200);
        echo json_encode($users_arr);
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}
