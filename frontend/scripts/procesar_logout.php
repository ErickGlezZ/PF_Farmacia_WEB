
<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\Middleware\AuthMiddleware;

// Iniciar la sesión para validar CSRF
Sesion::iniciar();

// Aceptar únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit;
}

// Validar el token CSRF
$tokenRecibido = $_POST['csrf_token'] ?? '';
$tokenSesion = $_SESSION['csrf_logout'] ?? '';

if (
    empty($tokenRecibido) ||
    empty($tokenSesion) ||
    !hash_equals($tokenSesion, $tokenRecibido)
) {
    http_response_code(403);
    exit('La solicitud de cierre de sesión no es válida.');
}

// Cerrar la autenticación JWT y destruir la sesión
AuthMiddleware::cerrarSesion();

// Regresar al login
header('Location: ../pages/login.php');
exit;
