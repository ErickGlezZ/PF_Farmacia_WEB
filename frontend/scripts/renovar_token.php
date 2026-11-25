<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Middleware\AuthMiddleware;

header('Content-Type: application/json; charset=utf-8');

// Evitar almacenamiento de respuestas
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// Verificar que exista una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

// Verificar que el usuario esté autenticado
try {

    // Esta comprobación valida el JWT y renueva su expiración.
    AuthMiddleware::verificarYRenovarToken();

    echo json_encode(['ok' => true]);

} catch (\Throwable $e) {

    error_log('Error al renovar JWT: ' . $e->getMessage());

    http_response_code(401);
    echo json_encode(['ok' => false]);
}
