<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\facade\FarmaciaFacade;

Sesion::iniciar();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/recuperar_password.php');
    exit;
}

// Validar CSRF
$csrf = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_recuperacion']) ||
    !hash_equals($_SESSION['csrf_recuperacion'], $csrf)
) {
    $_SESSION['mensaje_recuperacion'] =
        'La solicitud no es válida. Intenta nuevamente.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'danger';

    header('Location: ../pages/recuperar_password.php');
    exit;
}

// Consumir el token CSRF
unset($_SESSION['csrf_recuperacion']);

// Obtener nombre de usuario
$usuario = trim($_POST['usuario'] ?? '');

if ($usuario === '' || strlen($usuario) > 50) {

    $_SESSION['mensaje_recuperacion'] =
        'Ingresa un nombre de usuario válido.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'warning';

    header('Location: ../pages/recuperar_password.php');
    exit;
}

try {

    $farmacia = new FarmaciaFacade();

    $token = $farmacia->solicitarRecuperacionPassword($usuario);

    if ($token !== null) {

    header(
        'Location: ../pages/recuperar_password.php?token=' . urlencode($token)
    );
    exit;

} else {

    $_SESSION['mensaje_recuperacion'] =
        'No fue posible procesar la solicitud. Verifica tus datos o contacta al administrador.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'warning';

    header('Location: ../pages/recuperar_password.php');
    exit;
} 

} catch (\Throwable $e) {

    error_log('Error al procesar recuperación: ' . $e->getMessage());

    $_SESSION['mensaje_recuperacion'] =
        'Ocurrió un error al procesar la solicitud. Intenta nuevamente.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'danger';

    header('Location: ../pages/recuperar_password.php');
    exit;
}
