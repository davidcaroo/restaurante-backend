<?php

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(204);
    exit;
}

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/jwt_helper.php';


// include_once '../../config/database.php';

$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["message" => "No autenticado"]);
    exit;
}
$token = $matches[1];

try {
    $payload = jwt_decode($token);
    echo json_encode([
        "cliente" => [
            "id" => $payload->id,
            "email" => $payload->email,
            "nombre" => $payload->nombre,
            "telefono" => $payload->telefono ?? null
        ]
    ]);
    http_response_code(200); //
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Token inválido"]);
    exit;
}
