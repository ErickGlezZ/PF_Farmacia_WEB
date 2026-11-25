<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Middleware\AuthMiddleware;
use App\facade\FarmaciaFacade;

// Solamente administrador.
AuthMiddleware::requireRole(
    ['Administrador'],
    '../pages/login.php'
);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/usuarios/gestion_usuarios.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: ../pages/usuarios/gestion_usuarios.php?error=id');
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

try {

    $farmacia = new FarmaciaFacade();

    $resultado = $farmacia->actualizarEmpleado(
        $id,
        $nombre,
        $usuario,
        $password
    );

    if ($resultado) {

        header('Location: ../pages/usuarios/gestion_usuarios.php?actualizado=1');
        exit;

    }

    header('Location: ../pages/usuarios/gestion_usuarios.php?error=actualizacion');
    exit;

} catch (Throwable $e) {

    // Guardar el detalle en el registro de errores del servidor.
    error_log($e->getMessage());

    header('Location: ../pages/usuarios/gestion_usuarios.php?error=actualizacion');
    exit;

}
