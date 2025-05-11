<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Ruta al archivo de clave pública
$publicKeyPath = "public.pem"; // Asegúrate que la ruta sea correcta

// Obtener el JWT desde la cabecera HTTP
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    http_response_code(401); // Unauthorized
    die("Acceso denegado: No se encontró el token en la cabecera.");
}

// Extraer el token de la cabecera "Authorization: Bearer <TOKEN>"
$authHeader = $headers['Authorization'];
$token = str_replace("Bearer ", "", $authHeader);

if (empty($token)) {
    http_response_code(401);
    die("Acceso denegado: Token vacío o formato incorrecto en la cabecera.");
}

if (!file_exists($publicKeyPath)) {
    http_response_code(500); // Internal Server Error
    die("Error del servidor: El archivo public.pem no existe.");
}
if (!is_readable($publicKeyPath)) {
    http_response_code(500);
    die("Error del servidor: No se puede leer el archivo public.pem, revisa permisos.");
}

$publicKey = file_get_contents($publicKeyPath);

try {
    // Validar y decodificar el JWT
    $decoded = JWT::decode($token, new Key($publicKey, "RS256"));

    // Verificar si el token ha expirado
    if (isset($decoded->exp) && $decoded->exp < time()) {
        http_response_code(401); // Unauthorized
        die("Token expirado. Por favor, inicia sesión nuevamente.");
    }

    // Verificar si el usuario tiene permisos (rol 'admin')
    if (!isset($decoded->role) || $decoded->role !== 'admin') {
        http_response_code(403); // Forbidden
        die("Acceso denegado: No tienes permisos de administrador.");
    }

    // Si el token es válido y el usuario es admin, permitir acceso
    echo "Acceso permitido. Bienvenido, " . htmlspecialchars($decoded->user);

} catch (Firebase\JWT\ExpiredException $e) {
    http_response_code(401);
    die("Token expirado: " . $e->getMessage());
} catch (Firebase\JWT\SignatureInvalidException $e) {
    http_response_code(401);
    die("Token inválido (firma incorrecta): " . $e->getMessage());
} catch (Exception $e) { // Captura otras excepciones de JWT (formato, etc.)
    http_response_code(401);
    die("Token inválido: " . $e->getMessage());
}
?>