<?php

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
}
$sql = "SELECT * FROM subscription_types";
if (isset($id)) {
    $sql .= " WHERE id = ?";
}
$stmt = $db->prepare($sql);
$stmt->execute(isset($id) ? [$id] : null);

$num = $stmt->rowCount();

if ($num > 0) {
    if (isset($id)) {
        http_response_code(200);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $types = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($types, $row);
        }
        http_response_code(200);
        echo json_encode($types);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "Aucun type d'abonnement trouvé !"]);
}
