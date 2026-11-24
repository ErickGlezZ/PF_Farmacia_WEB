<?php

namespace App\Interfaces;

use App\Models\Usuario;

interface UsuarioRepositoryInterface
{
    // Registrar un usuario
    public function guardar(Usuario $usuario): bool;

    // Buscar un usuario por su ID
    public function buscarPorId(int $id): ?Usuario;

    // Buscar un usuario por su nombre de acceso
    public function buscarPorUsuario(string $usuario): ?Usuario;

    // Listar todos los usuarios
    public function listar(): array;

    // Actualizar los datos de un usuario
    public function actualizar(Usuario $usuario): bool;

    // Cambiar el estado de una cuenta
    public function cambiarEstado(int $id, int $estado): bool;

    // Comprobar si un nombre de usuario ya existe
    public function existeUsuario(string $usuario): bool;
}
