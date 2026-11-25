<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\Config\JWT;
use App\facade\FarmaciaFacade;

// Iniciar sesión para validar CSRF y manejar mensajes
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
    $_SESSION['mensaje_login'] =
        'La solicitud no es válida. Intenta nuevamente.';

    header('Location: ../pages/login.php');
    exit;
}

// Obtener respuesta del CAPTCHA
$tokenCaptcha = $_POST['cf-turnstile-response'] ?? '';

// Comprobar que se haya completado el CAPTCHA
if ($tokenCaptcha === '') {

    $_SESSION['mensaje_login'] =
        'Completa la verificación de seguridad.';

    header('Location: ../pages/login.php');
    exit;
}

try {

    // Obtener la clave secreta de Cloudflare
    $claveSecreta = require __DIR__ .
        '/../../backend/config/ClaveTurnstile.php';

    // Preparar los datos para validar el CAPTCHA
    $datosCaptcha = [
        'secret' => $claveSecreta,
        'response' => $tokenCaptcha
    ];

    // Validar el CAPTCHA directamente con Cloudflare
    $curl = curl_init(
        'https://challenges.cloudflare.com/turnstile/v0/siteverify'
    );

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($datosCaptcha),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10
    ]);

    $respuesta = curl_exec($curl);

    if ($respuesta === false) {
        throw new \RuntimeException(
            'No fue posible conectar con Cloudflare.'
        );
    }

    $codigoHTTP = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($codigoHTTP !== 200) {
        throw new \RuntimeException(
            'Cloudflare no pudo validar el CAPTCHA.'
        );
    }

    $resultadoCaptcha = json_decode($respuesta, true);

    // Diagnóstico temporal de Cloudflare
error_log(
    'Resultado CAPTCHA: ' .
    json_encode($resultadoCaptcha)
);  

    if (
        !is_array($resultadoCaptcha) ||
        ($resultadoCaptcha['success'] ?? false) !== true ||
        !in_array(
            $resultadoCaptcha['hostname'] ?? '',
            [
                'localhost',
                'farmaciasr-x.atwebpages.com'
            ],
            true
        )
    ) {
        error_log(
            'CAPTCHA rechazado. Hostname: ' .
            ($resultadoCaptcha['hostname'] ?? 'desconocido') .
            ' | Errores: ' .
            json_encode($resultadoCaptcha['error-codes'] ?? [])
        );

        $_SESSION['mensaje_login'] =
            'La verificación CAPTCHA no fue válida. Intenta nuevamente.';

        header('Location: ../pages/login.php');
        exit;
    }

    // Obtener los datos del formulario
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar campos vacíos
    if ($usuario === '' || $password === '') {

        $_SESSION['mensaje_login'] =
            'Ingresa tu usuario y contraseña.';

        header('Location: ../pages/login.php');
        exit;
    }

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

    // Obtener el ID del usuario autenticado
    $idUsuario = (int) $usuarioAutenticado->getIdUsuario();

    // Generar el JWT
    $tokenJWT = JWT::generar($idUsuario);

    // Regenerar el identificador de sesión
    session_regenerate_id(true);

    // Eliminar el token CSRF utilizado
    unset($_SESSION['csrf_login']);

    // Detectar si la conexión utiliza HTTPS
    $https = (
        isset($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== 'off'
    );

    // Guardar el JWT en una cookie segura
    setcookie(
        'access_token',
        $tokenJWT,
        [
            'expires' => time() + 300,
            'path' => '/',
            'secure' => $https,
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );

    // Redirigir al dashboard
    header('Location: ../pages/dashboard.php');
    exit;

} catch (\Throwable $e) {

    // Registrar el error real en el servidor
    error_log('Error en login: ' . $e->getMessage());

    $_SESSION['mensaje_login'] =
        'No fue posible completar la verificación de seguridad. Intenta nuevamente.';

    header('Location: ../pages/login.php');
    exit;
}
