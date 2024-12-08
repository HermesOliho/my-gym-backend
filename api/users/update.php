<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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

// Vérifier que toutes les informations nécessaires sont fournies
if (!empty($data->id) && !empty($data->name) && !empty($data->contact)) {
    // Assigner les valeurs aux propriétés de l'objet User
    $user->id = $data->id;
    $user->name = $data->name;
    $user->contact = $data->contact;
    $user->role = $data->role ?? "client";
    $user->subscription_type = $data->subscription ?? null;
    $user->start_date = $data->start_date ?? null;
    $user->end_date = $data->end_date ?? null;

    // Mettre à jour l'utilisateur
    if ($user->update()) {
        http_response_code(200);
        echo json_encode(["message" => "User was updated successfully.", "success" => true]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to update user.", "success" => false]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data.", "success" => false]);
}
