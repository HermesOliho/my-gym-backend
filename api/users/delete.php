<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure la base de données et le fichier de classe User
include_once '../../config/Database.php';
include_once '../../classes/User.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser l'objet User
$user = new User($db);

// Récupérer les données JSON
$data = json_decode(file_get_contents("php://input"));

// Vérifier que l'ID de l'utilisateur est fourni
if (!empty($data->id)) {
    // Assigner l'ID à l'objet User
    $user->id = $data->id;

    // Supprimer l'utilisateur
    if ($user->delete()) {
        http_response_code(200);
        echo json_encode(["message" => "User was deleted successfully.", "success" => true]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to delete user.", "success" => false]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data.", "success" => false]);
}
