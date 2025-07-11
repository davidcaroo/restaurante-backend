<?php
// api/reservas/read.php

// 1) Cabeceras CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// 2) Responder preflight y salir
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 3) Tipo de contenido JSON
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../../config/database.php';

// 4) Conexión a la base de datos
$db = (new Database())->getConnection();

// 5) Consulta de todas las reservas
$query = "
  SELECT
    id,
    cliente_id,
    mesa_id,
    fecha,
    hora_inicio,
    hora_fin,
    numero_personas,
    estado,
    detalles_reserva,
    creado_en
  FROM reservas
  ORDER BY fecha DESC, hora_inicio DESC
";
$stmt = $db->prepare($query);
$stmt->execute();

// 6) Formatear resultados
$reservas = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $reservas[] = [
        'id'               => (int)   $row['id'],
        'cliente_id'       => (int)   $row['cliente_id'],
        'mesa_id'          => (int)   $row['mesa_id'],
        'fecha'            =>          $row['fecha'],
        'hora_inicio'      =>          $row['hora_inicio'],
        'hora_fin'         =>          $row['hora_fin'],
        'numero_personas'  => (int)   $row['numero_personas'],
        'estado'           =>          $row['estado'],
        'detalles_reserva' =>          $row['detalles_reserva'],
        'creado_en'        =>          $row['creado_en'],
    ];
}

// 7) Devolver JSON
echo json_encode($reservas);