<?php

namespace App\Middleware;

use App\Config\Sesion;
use App\Config\JWT;
use App\facade\FarmaciaFacade;

class AuthMiddleware
{
    /**
     * Comprueba que exista un JWT válido
     * y que la cuenta del usuario continúe activa.
     */
    public static function requireLogin(string $rutaLogin): void
    {
        // Iniciar sesión para datos temporales
        Sesion::iniciar();

        // Evitar que el navegador conserve páginas protegidas
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Obtener el JWT de la cookie
        $token = $_COOKIE['access_token'] ?? '';

        // Comprobar que exista el token
        if ($token === '') {
            self::limpiarDatosAutenticacion();

            header("Location: {$rutaLogin}");
            exit;
        }

        // Validar el JWT
        $datosToken = JWT::validar($token);

        if ($datosToken === null) {
            self::limpiarDatosAutenticacion();

            header("Location: {$rutaLogin}");
            exit;
        }

        // Obtener el ID del usuario desde el JWT
        $idUsuario = (int) $datosToken['sub'];

        // Comprobar que la cuenta siga activa
        try {

            $farmacia = new FarmaciaFacade();

            $usuario = $farmacia->consultarUsuario($idUsuario);

            if (
                $usuario === null ||
                (int) $usuario->getEstado() !== 1
            ) {
                self::limpiarDatosAutenticacion();

                header("Location: {$rutaLogin}");
                exit;
            }

            // Actualizar los datos informativos desde MySQL.
            // Estas variables NO se utilizan para autenticar.
            $_SESSION['id_usuario'] = $usuario->getIdUsuario();
            $_SESSION['usuario_autenticado'] = $usuario->getUsuario();
            $_SESSION['nombre_usuario'] = $usuario->getNombre();
            $_SESSION['rol'] = $usuario->getRol();

            // Renovar el JWT después de verificar al usuario.
            self::renovarTokenJWT($idUsuario);

        } catch (\Throwable $e) {

            // Si no podemos verificar la cuenta, no concedemos acceso
            error_log('Error al verificar JWT: ' . $e->getMessage());

            http_response_code(503);
            exit('No fue posible verificar tu cuenta. Intenta nuevamente.');
        }
    }

    /**
     * Comprueba que el usuario tenga uno de los roles permitidos.
     */
    public static function requireRole(
        array $rolesPermitidos,
        string $rutaLogin
    ): void {

        self::requireLogin($rutaLogin);

        if (
            !in_array($_SESSION['rol'] ?? '', $rolesPermitidos, true)
        ) {
            http_response_code(403);
            exit('No tienes permisos para realizar esta operación.');
        }
    }

    /**
     * Elimina los datos de autenticación y la cookie JWT.
     */
    private static function limpiarDatosAutenticacion(): void
    {
        unset(
            $_SESSION['id_usuario'],
            $_SESSION['usuario_autenticado'],
            $_SESSION['nombre_usuario'],
            $_SESSION['rol']
        );

        self::eliminarCookieJWT();
    }

    /**
     * Renueva el JWT y su cookie por otros 5 minutos.
     */
    private static function renovarTokenJWT(int $idUsuario): void
    {
        $token = JWT::generar($idUsuario);

        $https = (
            isset($_SERVER['HTTPS']) &&
            $_SERVER['HTTPS'] !== 'off'
        );

        setcookie(
            'access_token',
            $token,
            [
                'expires' => time() + 300,
                'path' => '/',
                'secure' => $https,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

    // Actualizar también el valor disponible en esta petición.
    $_COOKIE['access_token'] = $token;
}


    /**
     * Verifica y renueva el JWT desde una petición AJAX.
     */
    public static function verificarYRenovarToken(): void
    {
        Sesion::iniciar();

        $token = $_COOKIE['access_token'] ?? '';

        if ($token === '') {
            http_response_code(401);
            throw new \RuntimeException('Token inexistente.');
        }

        $datosToken = JWT::validar($token);

        if ($datosToken === null) {
            http_response_code(401);
            throw new \RuntimeException('Token inválido o expirado.');
        }

        $idUsuario = (int) $datosToken['sub'];

        $farmacia = new FarmaciaFacade();
        $usuario = $farmacia->consultarUsuario($idUsuario);

        if (
            $usuario === null ||
            (int) $usuario->getEstado() !== 1
        ) {
            http_response_code(401);
            throw new \RuntimeException('Cuenta inactiva.');
        }

        self::renovarTokenJWT($idUsuario);
    }

    /**
     * Elimina la cookie que contiene el JWT.
     */
    private static function eliminarCookieJWT(): void
    {
        $https = (
            isset($_SERVER['HTTPS']) &&
            $_SERVER['HTTPS'] !== 'off'
        );

        setcookie(
            'access_token',
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => $https,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        unset($_COOKIE['access_token']);
    }

    /**
     * Cierra la autenticación y destruye la sesión temporal.
     */
    public static function cerrarSesion(): void
    {
        Sesion::iniciar();

        // Eliminar el JWT del navegador
        self::eliminarCookieJWT();

        // Destruir los datos de sesión
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {

            $parametros = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $parametros['path'],
                    'domain' => $parametros['domain'],
                    'secure' => $parametros['secure'],
                    'httponly' => $parametros['httponly'],
                    'samesite' => $parametros['samesite']
                ]
            );
        }

        session_destroy();
    }
}
