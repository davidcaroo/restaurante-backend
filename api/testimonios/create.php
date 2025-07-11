<?php
// api/testimonios/create.php

// 1) CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 2) JSON
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../../config/database.php';
$db = (new Database())->getConnection();

// 3) Leer body
$data = json_decode(file_get_contents("php://input"), true);

// 4) Validar campos mínimos
if (empty($data['nombre_cliente']) || empty($data['comentario'])) {
    http_response_code(400);
    echo json_encode(["message" => "Nombre y comentario son obligatorios"]);
    exit;
}

// 5) Insertar en BD
try {
    $stmt = $db->prepare("
      INSERT INTO testimonios
        (nombre_cliente, cargo, comentario, imagen_url)
      VALUES
        (:nombre, :cargo, :comentario, :imagen_url)
    ");

    $stmt->bindParam(':nombre',      $data['nombre_cliente']);
    $stmt->bindParam(':cargo',       $data['cargo']);
    $stmt->bindParam(':comentario',  $data['comentario']);
    $stmt->bindParam(':imagen_url',  $data['imagen_url']);

    $stmt->execute();

    http_response_code(201);
    echo json_encode([
        "message" => "Testimonio guardado",
        "id"      => $db->lastInsertId()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error al guardar", "error" => $e->getMessage()]);
}