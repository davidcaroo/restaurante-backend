<?php
// api/mesas/read.php

// 1) CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 2) JSON
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../../config/database.php';
$db = (new Database())->getConnection();

// 3) Consulta ajustada
$query = "
  SELECT
    id,
    tipo,
    capacidad,
    estado,
    creado_en
  FROM mesas
  ORDER BY id
";
$stmt = $db->prepare($query);
$stmt->execute();

// 4) Formatear salida
$mesas = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $mesas[] = [
        'id'        => (int)  $r['id'],
        'tipo'      =>        $r['tipo'],
        'capacidad' => (int)  $r['capacidad'],
        'estado'    =>        $r['estado'],
        'creado_en' =>        $r['creado_en'],
    ];
}

// 5) Devolver JSON
echo json_encode($mesas);