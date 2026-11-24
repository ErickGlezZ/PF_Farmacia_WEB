<?php

namespace App\Middleware;

use App\Config\Sesion;
use App\facade\FarmaciaFacade;

class AuthMiddleware
{
    /**
     * Comprueba que exista una sesión válida
     * y que la cuenta continúe activa.
     */
    public static function requireLogin(string $rutaLogin): void
    {
        // Iniciar sesión de forma segura
        Sesion::iniciar();

        // Evitar que el navegador conserve páginas protegidas
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Comprobar que exista un usuario autenticado
        if (
            !isset($_SESSION['id_usuario']) ||
            !isset($_SESSION['usuario_autenticado'])
        ) {
            header("Location: {$rutaLogin}");
            exit;
        }

        // Comprobar que la cuenta siga activa
        try {

            $farmacia = new FarmaciaFacade();

            $usuario = $farmacia->consultarUsuario(
                (int) $_SESSION['id_usuario']
            );

            if (
                $usuario === null ||
                (int) $usuario->getEstado() !== 1
            ) {
                self::cerrarSesion();

                header("Location: {$rutaLogin}");
                exit;
            }

            // Actualizar el rol desde la base de datos
            $_SESSION['rol'] = $usuario->getRol();
            $_SESSION['nombre_usuario'] = $usuario->getNombre();

        } catch (\Throwable $e) {

            // Si no podemos verificar la cuenta, no concedemos acceso
            http_response_code(503);
            exit('No fue posible verificar la sesión. Intenta nuevamente.');
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

        if (!in_array($_SESSION['rol'], $rolesPermitidos, true)) {
            http_response_code(403);
            exit('No tienes permisos para realizar esta operación.');
        }
    }

    /**
     * Destruye la sesión actual.
     */
    public static function cerrarSesion(): void
    {
        Sesion::iniciar();

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
