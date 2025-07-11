<?php
// api/testimonios/read.php

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

// 5) Consulta de testimonios
$query = "
  SELECT
    id,
    nombre_cliente,
    cargo,
    comentario,
    imagen_url,
    creado_en
  FROM testimonios
  ORDER BY creado_en DESC
";
$stmt = $db->prepare($query);
$stmt->execute();

// 6) Formatear resultados
$testimonios = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $testimonios[] = [
    'id'             => (int)   $row['id'],
    'nombre_cliente' =>          $row['nombre_cliente'],
    'cargo'          =>          $row['cargo'],
    'comentario'     =>          $row['comentario'],
    'imagen_url'     =>          $row['imagen_url'],
    'fecha'          =>          $row['creado_en'],
  ];
}

// 7) Devolver JSON
echo json_encode($testimonios);