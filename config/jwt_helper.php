<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv; // ¡Necesario!


if (!getenv('JWT_SECRET') && empty($_ENV['JWT_SECRET'])) {
    if (file_exists(__DIR__ . '/../.env')) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }
}

$jwtSecret = getenv('JWT_SECRET') ?: ($_ENV['JWT_SECRET'] ?? null);


if (!$jwtSecret) {
    die(json_encode([
        "message" => "No se encontró JWT_SECRET en .env o variables de entorno"
    ]));
}

function jwt_encode($data)
{
    global $jwtSecret;
    return JWT::encode($data, $jwtSecret, 'HS256');
}

function jwt_decode($jwt)
{
    global $jwtSecret;
    return JWT::decode($jwt, new Key($jwtSecret, 'HS256'));
}

function jwt_verify($jwt)
{
    try {
        jwt_decode($jwt);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
