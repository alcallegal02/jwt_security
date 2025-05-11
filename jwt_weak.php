<?php
// Esta línea es ESENCIAL. Carga todas las clases de las librerías instaladas por Composer.
require 'vendor/autoload.php';

// Esto importa la clase JWT para que puedas usarla como 'JWT::metodo()' en lugar de 'Firebase\JWT\JWT::metodo()'
use Firebase\JWT\JWT;

$key = "secret"; // Clave débil
$payload = [
    "user" => "admin",
    "role" => "admin"
];

// Generar el JWT usando HS256
$jwt = JWT::encode($payload, $key, "HS256");

echo "JWT Generado: " . $jwt;
?>