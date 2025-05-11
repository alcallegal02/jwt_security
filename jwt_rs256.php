<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;

$privateKey = file_get_contents("private.pem"); // Carga la clave privada

$payload = [
    "user" => "admin",
    "role" => "admin",
    "iat" => time(),          // Momento en que se emitió el token
    "exp" => time() + 3600  // El token expira en 1 hora (3600 segundos)
];

$jwt = JWT::encode($payload, $privateKey, "RS256");

echo "JWT Seguro Generado:\n" . $jwt . "\n";
?>