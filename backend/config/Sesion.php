<?php

namespace App\Config;

class Sesion
{
    /**
     * Inicia la sesión PHP para manejar datos temporales
     * como los tokens CSRF y mensajes informativos.
     */
    public static function iniciar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {

            // Evitar identificadores de sesión proporcionados por el usuario.
            ini_set('session.use_strict_mode', '1');

            // Permitir únicamente el uso de cookies para la sesión.
            ini_set('session.use_only_cookies', '1');

            // Detectar si la conexión utiliza HTTPS.
            $https = (
                isset($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] !== 'off'
            );

            // Configurar la cookie de sesión.
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $https,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            // Iniciar la sesión.
            session_start();
        }
    }
}
