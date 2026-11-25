<?php

namespace App\Controllers;

use App\Services\RecuperacionPasswordService;

class RecuperacionPasswordController
{
    private RecuperacionPasswordService $recuperacionService;

    public function __construct(
        RecuperacionPasswordService $recuperacionService
    ) {
        $this->recuperacionService = $recuperacionService;
    }

    /**
     * Solicitar recuperación de contraseña.
     */
    public function solicitar(string $usuario): ?string
    {
        return $this->recuperacionService->solicitar($usuario);
    }

    /**
     * Validar el token de recuperación.
     */
    public function validarToken(string $token): ?array
    {
        return $this->recuperacionService->validarToken($token);
    }

    /**
     * Restablecer contraseña.
     */
    public function restablecer(
        string $token,
        string $password,
        string $confirmacion
    ): bool {
        return $this->recuperacionService->restablecer(
            $token,
            $password,
            $confirmacion
        );
    }
}
