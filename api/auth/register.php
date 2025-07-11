<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: http://localhost:3000");


// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

include_once '../../config/database.php';

// Recibe datos del POST
$data = json_decode(file_get_contents("php://input"));

if (
    !isset($data->nombre) ||
    !isset($data->email) ||
    !isset($data->password)
) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan datos obligatorios"]);
    exit;
}

$db = (new Database())->getConnection();

// Revisar si el email ya existe
$stmt = $db->prepare("SELECT id FROM clientes WHERE email = ?");
$stmt->execute([$data->email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["message" => "El email ya está registrado"]);
    exit;
}

// Insertar el cliente
$passwordHash = password_hash($data->password, PASSWORD_BCRYPT);
$telefono = isset($data->telefono) ? $data->telefono : null;

$stmt = $db->prepare("INSERT INTO clientes (nombre, email, telefono, password, creado_en) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([
    $data->nombre,
    $data->email,
    $telefono,
    $passwordHash
]);

$cliente_id = $db->lastInsertId();

http_response_code(201);
echo json_encode([
    "cliente" => [
        "id" => $cliente_id,
        "nombre" => $data->nombre,
        "email" => $data->email,
        "telefono" => $telefono
    ]
]);
exit;
