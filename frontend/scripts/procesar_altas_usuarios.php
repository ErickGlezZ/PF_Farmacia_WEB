<?php

session_start();

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;
use App\Middleware\AuthMiddleware;

// Verificar que solamente el administrador pueda ejecutar el registro.
AuthMiddleware::requireRole(
    ['Administrador'],
    '../pages/login.php'
);

// Verificar que la petición sea POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: ../pages/usuarios/registrar_usuario.php');
    exit;

}

try {

    // Obtener los datos del formulario.
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    // Crear la fachada.
    $farmacia = new FarmaciaFacade();

    // Registrar usuario.
    $resultado = $farmacia->registrarUsuario(
        $nombre,
        $usuario,
        $password,
        $rol
    );

    if ($resultado) {

        $_SESSION['mensaje'] = 'Usuario registrado correctamente.';
        $_SESSION['tipo_mensaje'] = 'success';

    } else {

        $_SESSION['mensaje'] = 'No fue posible registrar al usuario.';
        $_SESSION['tipo_mensaje'] = 'danger';

    }

} catch (Throwable $e) {

    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = 'danger';

}

// Regresar a Gestión de usuarios.
header('Location: ../pages/usuarios/gestion_usuarios.php');
exit;
