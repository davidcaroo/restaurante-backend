<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../../config/database.php';
require_once '../../config/jwt_helper.php';

$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["message" => "No autenticado"]);
    exit;
}
$token = $matches[1];

try {
    $payload = jwt_decode($token); // Tu helper de JWT debe lanzar excepción si no es válido
    echo json_encode([
        "id" => $payload->id,
        "email" => $payload->email,
        "role" => $payload->role,
        "nombre" => $payload->nombre
    ]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Token inválido"]);
}
http_response_code(200); // OK