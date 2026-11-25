<?php

namespace App\Services;

use App\DAO\UsuarioDAO;
use App\DAO\RecuperacionPasswordDAO;
use App\Config\JWT;

class RecuperacionPasswordService
{
    private UsuarioDAO $usuarioDAO;
    private RecuperacionPasswordDAO $recuperacionDAO;

    public function __construct(
        UsuarioDAO $usuarioDAO,
        RecuperacionPasswordDAO $recuperacionDAO
    ) {
        $this->usuarioDAO = $usuarioDAO;
        $this->recuperacionDAO = $recuperacionDAO;
    }

    /**
     * Generar una solicitud de recuperación.
     */
    public function solicitar(string $usuario): ?string
    {
        $usuarioEncontrado = $this->usuarioDAO->buscarPorUsuario($usuario);

        if (
            $usuarioEncontrado === null ||
            $usuarioEncontrado->getEstado() !== 1
        ) {
            return null;
        }

        $idUsuario = $usuarioEncontrado->getIdUsuario();

        $datosToken = JWT::generarRecuperacion($idUsuario);

        $jtiHash = hash('sha256', $datosToken['jti']);

        $this->recuperacionDAO->registrar(
            $idUsuario,
            $jtiHash,
            $datosToken['expiracion']
        );

        return $datosToken['token'];
    }

    /**
     * Validar un token de recuperación.
     */
    public function validarToken(string $token): ?array
    {
        $payload = JWT::validarRecuperacion($token);

        if ($payload === null) {
            return null;
        }

        $jtiHash = hash('sha256', $payload['jti']);

        $recuperacion = $this->recuperacionDAO
            ->consultarVigente($jtiHash);

        if (
            $recuperacion === null ||
            (int) $recuperacion['id_usuario'] !== $payload['sub']
        ) {
            return null;
        }

        return $payload;
    }

    /**
     * Restablecer la contraseña.
     */
    public function restablecer(
        string $token,
        string $password,
        string $confirmacion
    ): bool {

        if (
            empty($password) ||
            strlen($password) < 8 ||
            $password !== $confirmacion
        ) {
            return false;
        }

        $payload = $this->validarToken($token);

        if ($payload === null) {
            return false;
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $jtiHash = hash('sha256', $payload['jti']);

        return $this->recuperacionDAO->cambiarPassword(
            $jtiHash,
            $payload['sub'],
            $passwordHash
        );
    }
}
