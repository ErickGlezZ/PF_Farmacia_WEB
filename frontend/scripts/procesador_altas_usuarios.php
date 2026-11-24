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

    header('Location: ../pages/registrar_usuario.php');
    exit;

}

try {

    // Obtener los datos del formulario.
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Crear la fachada.
    $farmacia = new FarmaciaFacade();

    // Registrar empleado.
    $resultado = $farmacia->registrarEmpleado(
        $nombre,
        $usuario,
        $password
    );

    if ($resultado) {

        $_SESSION['mensaje'] = 'Empleado registrado correctamente.';
        $_SESSION['tipo_mensaje'] = 'success';

    } else {

        $_SESSION['mensaje'] = 'No fue posible registrar al empleado.';
        $_SESSION['tipo_mensaje'] = 'danger';

    }

} catch (Throwable $e) {

    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = 'danger';

}

// Regresar al formulario.
header('Location: ../pages/registrar_usuario.php');
exit;

