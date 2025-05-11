<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;

$privateKeyPath = "private.pem"; // Asegúrate que la ruta sea correcta
if (!file_exists($privateKeyPath) || !is_readable($privateKeyPath)) {
    die("Error: No se puede leer el archivo private.pem. Verifica la ruta y los permisos.");
}
$privateKey = file_get_contents($privateKeyPath);

$payload = [
    "user" => "admin",
    "role" => "admin",
    "iat" => time(),
    "exp" => time() + 3600 // Expira en 1 hora
];

$jwt = JWT::encode($payload, $privateKey, "RS256");

echo "JWT Generado para API:\n" . $jwt . "\n";
?>