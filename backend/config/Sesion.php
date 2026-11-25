<?php

namespace App\Config;

class Sesion
{
    // Tiempo máximo de inactividad: 30min.
    private const TIEMPO_INACTIVIDAD = 1800;

    public static function iniciar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {

            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');

            $https = (
                isset($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] !== 'off'
            );

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $https,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }

        // Comprobar el tiempo de inactividad
        if (isset($_SESSION['id_usuario'])) {

            $ultimaActividad = $_SESSION['ultima_actividad'] ?? time();

            if ((time() - $ultimaActividad) >= self::TIEMPO_INACTIVIDAD) {

                // Eliminar los datos de sesión.
                $_SESSION = [];

                // Eliminar la cookie de sesión.
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

                // Destruir la sesión.
                session_destroy();

                return;
            }

            // Actualizar la última actividad.
            $_SESSION['ultima_actividad'] = time();
        }
    }
}
