<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Cargar .env una vez
if (!getenv('JWT_SECRET')) {
    if (file_exists(__DIR__ . '/../.env')) {
        require_once __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }
}

$jwtSecret = getenv('JWT_SECRET');

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
