<?php
// api/reservas/create.php

// 1) Cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 2) JSON de respuesta
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../../config/database.php';
$db = (new Database())->getConnection();

// 3) Leer el body
$input = json_decode(file_get_contents("php://input"), true);

// 4) Validar parámetros
$required = ['mesa_id', 'fecha', 'hora_inicio', 'hora_fin', 'numero_personas'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode([
            "message" => "El campo '$field' es obligatorio"
        ]);
        exit;
    }
}

// 5) Preparar e insertar
try {
    $stmt = $db->prepare("
      INSERT INTO reservas
        (cliente_id, mesa_id, fecha, hora_inicio, hora_fin, numero_personas, detalles_reserva)
      VALUES
        (:cliente_id, :mesa_id, :fecha, :hora_inicio, :hora_fin, :numero_personas, :detalles_reserva)
    ");
    // si aún no tienes cliente logueado, usa un ID fijo o adapta:
    $clienteId = $input['cliente_id'] ?? 1;

    $stmt->bindParam(':cliente_id',       $clienteId,          PDO::PARAM_INT);
    $stmt->bindParam(':mesa_id',          $input['mesa_id'],   PDO::PARAM_INT);
    $stmt->bindParam(':fecha',            $input['fecha']);
    $stmt->bindParam(':hora_inicio',      $input['hora_inicio']);
    $stmt->bindParam(':hora_fin',         $input['hora_fin']);
    $stmt->bindParam(':numero_personas',  $input['numero_personas'], PDO::PARAM_INT);
    $stmt->bindParam(':detalles_reserva', $input['detalles_reserva']);

    $stmt->execute();

    http_response_code(201);
    echo json_encode([
        "message" => "Reserva creada con éxito",
        "id"      => (int)$db->lastInsertId()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "message" => "Error interno al guardar la reserva",
        "error"   => $e->getMessage()
    ]);
}