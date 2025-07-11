<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: http://localhost:3000");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

include_once '../../config/database.php';
require_once '../../config/jwt_helper.php';

$data = json_decode(file_get_contents("php://input"));

// --- ¡ADAPTA LA TABLA A 'clientes'! --- //
if (!isset($data->email, $data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan datos"]);
    exit;
}

$db = (new Database())->getConnection();

$stmt = $db->prepare("SELECT * FROM clientes WHERE email = ?");
$stmt->execute([$data->email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data->password, $user['password'])) {
    // CREA el JWT payload:
    $payload = [
        "id" => $user['id'],
        "email" => $user['email'],
        "nombre" => $user['nombre'],
        "telefono" => $user['telefono']
        // Puedes agregar más campos si quieres
    ];
    $token = jwt_encode($payload);

    echo json_encode([
        "access_token" => $token, 
        "cliente" => [
            "id" => $user['id'],
            "email" => $user['email'],
            "nombre" => $user['nombre'],
            "telefono" => $user['telefono']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Credenciales incorrectas"]);
}
exit;