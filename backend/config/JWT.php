<?php

namespace App\Config;

class JWT
{
    private const ALGORITMO = 'HS256';
    private const TIEMPO_EXPIRACION = 300; // 5 minutos

    /**
     * Obtener la clave secreta.
     */
    private static function obtenerClave(): string
    {
        $clave = require __DIR__ . '/ClaveJWT.php';

        if (!is_string($clave) || strlen($clave) < 64) {
            throw new \RuntimeException(
                'La clave secreta JWT no es válida.'
            );
        }

        return $clave;
    }

    /**
     * Codificar datos en Base64 URL Safe.
     */
    private static function base64UrlEncode(string $dato): string
    {
        return rtrim(strtr(base64_encode($dato), '+/', '-_'), '=');
    }

    /**
     * Decodificar datos Base64 URL Safe.
     */
    private static function base64UrlDecode(string $dato): string|false
    {
        $dato = strtr($dato, '-_', '+/');

        $relleno = strlen($dato) % 4;

        if ($relleno > 0) {
            $dato .= str_repeat('=', 4 - $relleno);
        }

        return base64_decode($dato, true);
    }

    /**
     * Generar un JWT.
     */
    public static function generar(int $idUsuario): string
    {
        $ahora = time();

        $encabezado = [
            'alg' => self::ALGORITMO,
            'typ' => 'JWT'
        ];

        $payload = [
            'iss' => 'PF_Farmacia_WEB',
            'sub' => $idUsuario,
            'iat' => $ahora,
            'exp' => $ahora + self::TIEMPO_EXPIRACION,
            'jti' => bin2hex(random_bytes(16))
        ];

        $header = self::base64UrlEncode(
            json_encode($encabezado)
        );

        $contenido = self::base64UrlEncode(
            json_encode($payload)
        );

        $mensaje = $header . '.' . $contenido;

        $firma = hash_hmac(
            'sha256',
            $mensaje,
            self::obtenerClave(),
            true
        );

        return $mensaje . '.' . self::base64UrlEncode($firma);
    }

    /**
     * Validar un JWT.
     *
     * Devuelve los datos del token si es válido.
     * Devuelve null si no es válido.
     */
    public static function validar(string $token): ?array
    {
        try {

            $partes = explode('.', $token);

            if (count($partes) !== 3) {
                return null;
            }

            [$header, $contenido, $firma] = $partes;

            $datosHeader = self::base64UrlDecode($header);
            $datosPayload = self::base64UrlDecode($contenido);
            $firmaRecibida = self::base64UrlDecode($firma);

            if (
                $datosHeader === false ||
                $datosPayload === false ||
                $firmaRecibida === false
            ) {
                return null;
            }

            $encabezado = json_decode($datosHeader, true);
            $payload = json_decode($datosPayload, true);

            if (
                !is_array($encabezado) ||
                !is_array($payload) ||
                ($encabezado['alg'] ?? '') !== self::ALGORITMO ||
                ($encabezado['typ'] ?? '') !== 'JWT'
            ) {
                return null;
            }

            $firmaEsperada = hash_hmac(
                'sha256',
                $header . '.' . $contenido,
                self::obtenerClave(),
                true
            );

            if (!hash_equals($firmaEsperada, $firmaRecibida)) {
                return null;
            }

            if (
                ($payload['iss'] ?? '') !== 'PF_Farmacia_WEB' ||
                !isset($payload['sub'], $payload['iat'], $payload['exp']) ||
                !is_int($payload['sub']) ||
                $payload['sub'] <= 0 ||
                !is_int($payload['iat']) ||
                !is_int($payload['exp']) ||
                $payload['iat'] > time() ||
                $payload['exp'] <= time() ||
                $payload['exp'] <= $payload['iat'] ||
                ($payload['exp'] - $payload['iat']) > self::TIEMPO_EXPIRACION
            ) {
                return null;
            }

            return $payload;

        } catch (\Throwable $e) {

            return null;
        }
    }
}
