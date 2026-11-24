<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\facade\FarmaciaFacade;

// Iniciar sesión
Sesion::iniciar();

// Aceptar únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit;
}

// Validar el token CSRF
$tokenRecibido = $_POST['csrf_token'] ?? '';
$tokenSesion = $_SESSION['csrf_login'] ?? '';

if (
    empty($tokenRecibido) ||
    empty($tokenSesion) ||
    !hash_equals($tokenSesion, $tokenRecibido)
) {
    $_SESSION['mensaje_login'] = 'La solicitud no es válida. Intenta nuevamente.';
    header('Location: ../pages/login.php');
    exit;
}

// Obtener los datos del formulario
$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

// Validar campos vacíos
if ($usuario === '' || $password === '') {
    $_SESSION['mensaje_login'] = 'Ingresa tu usuario y contraseña.';
    header('Location: ../pages/login.php');
    exit;
}

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Autenticar al usuario
    $usuarioAutenticado = $farmacia->autenticarUsuario(
        $usuario,
        $password
    );

    // Comprobar el resultado
    if ($usuarioAutenticado === null) {

        $_SESSION['mensaje_login'] =
            'Usuario o contraseña incorrectos, o cuenta desactivada.';

        header('Location: ../pages/login.php');
        exit;
    }

    // Regenerar el identificador para evitar fijación de sesión
    session_regenerate_id(true);

    // Guardar únicamente los datos necesarios
    $_SESSION['id_usuario'] = $usuarioAutenticado->getIdUsuario();
    $_SESSION['usuario_autenticado'] = $usuarioAutenticado->getUsuario();
    $_SESSION['nombre_usuario'] = $usuarioAutenticado->getNombre();
    $_SESSION['rol'] = $usuarioAutenticado->getRol();

    // El token de login ya no se necesita
    unset($_SESSION['csrf_login']);

    // Redirigir al dashboard
    header('Location: ../pages/dashboard.php');
    exit;

} catch (\Throwable $e) {

    // Registrar el error real en el servidor
    error_log('Error en login: ' . $e->getMessage());

    $_SESSION['mensaje_login'] =
        'Ocurrió un problema al iniciar sesión. Intenta nuevamente.';

    header('Location: ../pages/login.php');
    exit;
}
