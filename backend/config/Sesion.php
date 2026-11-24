<?php

namespace App\Config;

class Sesion
{
    public static function iniciar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {

            // Configuración de seguridad de la sesión
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');

            // Detectar si la conexión utiliza HTTPS
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
    }
}
