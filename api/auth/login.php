<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


include_once '../../config/database.php';
require_once '../../config/jwt_helper.php'; // Aquí debes tener tu librería de JWT

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email, $data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan datos"]);
    exit;
}

$db = (new Database())->getConnection();

$stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$data->email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data->password, $user['password'])) {
    // Generar JWT
    $payload = [
        "id" => $user['id'],
        "email" => $user['email'],
        "role" => $user['role'],
        "nombre" => $user['nombre']
    ];
    $token = jwt_encode($payload);

    echo json_encode([
        "access_token" => $token,
        "user" => [
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role'],
            "nombre" => $user['nombre']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Credenciales incorrectas"]);
}
http_response_code(200); // OK
exit;