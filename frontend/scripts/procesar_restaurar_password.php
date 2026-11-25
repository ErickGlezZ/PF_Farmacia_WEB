
<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\facade\FarmaciaFacade;

Sesion::iniciar();

// Verificar que la solicitud sea POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit;
}

// Obtener el token CSRF.
$csrf = $_POST['csrf_token'] ?? '';

// Validar el token CSRF.
if (
    empty($_SESSION['csrf_restaurar_password']) ||
    !hash_equals($_SESSION['csrf_restaurar_password'], $csrf)
) {

    $_SESSION['mensaje_recuperacion'] =
        'La solicitud no es válida. Intenta nuevamente.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'danger';

    header('Location: ../pages/recuperar_password.php');
    exit;
}

// Eliminar el token CSRF después de validarlo.
unset($_SESSION['csrf_restaurar_password']);

// Obtener los datos del formulario.
$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$confirmacion = $_POST['confirmacion'] ?? '';

// Validar que los campos no estén vacíos.
if (
    $token === '' ||
    $password === '' ||
    $confirmacion === ''
) {

    $_SESSION['mensaje_recuperacion'] =
        'Completa todos los campos.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'warning';

    header('Location: ../pages/recuperar_password.php');
    exit;
}

// Validar la longitud de la contraseña.
if (strlen($password) < 8 || strlen($password) > 72) {

    $_SESSION['mensaje_recuperacion'] =
        'La contraseña debe tener entre 8 y 72 caracteres.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'warning';

    header('Location: ../pages/recuperar_password.php?token=' . urlencode($token));
    exit;
}

// Verificar que las contraseñas coincidan.
if ($password !== $confirmacion) {

    $_SESSION['mensaje_recuperacion'] =
        'Las contraseñas no coinciden.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'warning';

    header('Location: ../pages/recuperar_password.php?token=' . urlencode($token));
    exit;
}

try {

    // Crear la instancia de la fachada.
    $farmacia = new FarmaciaFacade();

    // Intentar restablecer la contraseña.
    $resultado = $farmacia->restablecerPassword(
        $token,
        $password,
        $confirmacion
    );

    if ($resultado) {

        // Mensaje para mostrar en el inicio de sesión.
        $_SESSION['mensaje_login'] =
            'Tu contraseña se actualizó correctamente. Ya puedes iniciar sesión.';

        $_SESSION['tipo_mensaje_login'] = 'success';

        header('Location: ../pages/login.php');
        exit;

    } else {

        $_SESSION['mensaje_recuperacion'] =
            'El enlace no es válido, expiró o ya fue utilizado.';

        $_SESSION['tipo_mensaje_recuperacion'] = 'danger';

        header('Location: ../pages/recuperar_password.php');
        exit;
    }

} catch (\Throwable $e) {

    // Registrar el error para revisarlo en los registros del servidor.
    error_log('Error al restablecer contraseña: ' . $e->getMessage());

    $_SESSION['mensaje_recuperacion'] =
        'No fue posible actualizar la contraseña. Intenta nuevamente.';

    $_SESSION['tipo_mensaje_recuperacion'] = 'danger';

    header('Location: ../pages/recuperar_password.php');
    exit;
}
