<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$publicKeyPath = "public.pem"; // Ruta a tu clave pública
// Pega aquí el JWT generado con jwt_rs256.php
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VyIjoiYWRtaW4iLCJyb2xlIjoiYWRtaW4iLCJpYXQiOjE3NDY4MTUzMzIsImV4cCI6MTc0NjgxODkzMn0.nSXCwR8nFuvGeKKZm46YznZ8Y1-2I5m5bebHyNNFSdITEdegYm2NeVscMAs02sM1G2Wx_91Wxej6_dExdhhJg_NvXO5ANfOpPXZwe_mEV3mYyb-HqKH4jvVjK-GE5Xyyz-LN5ZyB0CMSfTGzloDcQp-p7eMB40ip4XJwD6bxle2debYh-CVdjN0L_KyrIIlDBhTnzP3n-a6rrpr3fjAfl3c-gt-6eB6MDMH_lu8I1UjcJ2-_AeU4GxQuHuFy9QOrVqlStacYTLZ_5nsnWeFKcCFpb1G8JIddks8bMijtiok3CJrJ89y3a3Q9q8sX2BTs_fJCuCSkXB8UpeAkeWm91w";

if (!file_exists($publicKeyPath)) {
    die("Error: El archivo public.pem no existe.");
}
if (!is_readable($publicKeyPath)) {
    die("Error: No se puede leer el archivo public.pem, revisa permisos.");
}

$publicKey = file_get_contents($publicKeyPath);

try {
    // Decodificar y validar la firma usando la clave pública y el algoritmo RS256
    $decoded = JWT::decode($token, new Key($publicKey, "RS256"));
    echo "Token válido! Información decodificada:\n";
    print_r($decoded);
} catch (Exception $e) {
    echo "Token inválido: " . $e->getMessage();
}
?>