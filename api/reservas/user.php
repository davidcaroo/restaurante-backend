<?php
// CORS HEADERS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(204);
    exit;
}
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../config/jwt_helper.php';

// 1. AUTENTICACIÓN JWT
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
    $user_id = $payload->id; // Cambia según el nombre que pusiste en tu payload (normalmente es 'id' o 'user_id')
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Token inválido"]);
    exit;
}

// 2. OBTENER RESERVAS SOLO DEL USUARIO AUTENTICADO
$db = (new Database())->getConnection();

$query = "SELECT * FROM reservas WHERE cliente_id = ? ORDER BY fecha DESC, hora_inicio DESC";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reservas);
exit;
