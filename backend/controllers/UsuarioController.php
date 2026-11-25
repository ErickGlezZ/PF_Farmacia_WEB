<?php

namespace App\Controllers;

use App\Services\UsuarioService;

class UsuarioController
{
    private UsuarioService $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    // Registrar un nuevo usuario
    public function registrar(
        string $nombre,
        string $usuario,
        string $password,
        string $rol
    ): bool {

        return $this->usuarioService->registrarUsuario(
            $nombre,
            $usuario,
            $password,
            $rol
        );
    }

    // Consultar un usuario por su ID
    public function consultar(int $id)
    {
        return $this->usuarioService->obtenerUsuario($id);
    }

    // Listar todos los usuarios
    public function listar(): array
    {
        return $this->usuarioService->listarUsuarios();
    }

    // Actualizar los datos de un empleado
    public function actualizar(
        int $id,
        string $nombre,
        string $usuario,
        ?string $password = null
    ): bool {

        return $this->usuarioService->actualizarEmpleado(
            $id,
            $nombre,
            $usuario,
            $password
        );
    }

    // Activar o desactivar una cuenta
    public function cambiarEstado(int $id, int $estado): bool
    {
        return $this->usuarioService->cambiarEstado(
            $id,
            $estado
        );
    }

    // Validar las credenciales de inicio de sesión
    public function autenticar(
        string $usuario,
        string $password
    ) {

        return $this->usuarioService->autenticar(
            $usuario,
            $password
        );
    }
}
