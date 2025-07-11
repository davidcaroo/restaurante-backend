<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


include_once '../../config/database.php';
require_once '../../config/jwt_helper.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->nombre, $data->email, $data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan datos"]);
    exit;
}

$db = (new Database())->getConnection();

// Verificar si ya existe
$stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$data->email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["message" => "El email ya está registrado"]);
    exit;
}

// Registrar usuario
$passwordHash = password_hash($data->password, PASSWORD_BCRYPT);
$role = $data->role ?? 'client'; // Por defecto cliente

$stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$data->nombre, $data->email, $passwordHash, $role]);
$user_id = $db->lastInsertId();

$payload = [
    "id" => $user_id,
    "email" => $data->email,
    "role" => $role,
    "nombre" => $data->nombre
];
$token = jwt_encode($payload);

echo json_encode([
    "access_token" => $token,
    "user" => [
        "id" => $user_id,
        "email" => $data->email,
        "role" => $role,
        "nombre" => $data->nombre
    ]
]);
http_response_code(201); // Creado