<?php

namespace App\Services;

use App\Models\Usuario;
use App\Interfaces\UsuarioRepositoryInterface;

class UsuarioService
{
    private UsuarioRepositoryInterface $usuarioDAO;

    public function __construct(UsuarioRepositoryInterface $usuarioDAO)
    {
        $this->usuarioDAO = $usuarioDAO;
    }

    
    // Registrar un nuevo usuario
    public function registrarUsuario(
        string $nombre,
        string $usuario,
        string $password,
        string $rol
    ): bool {

        $nombre = trim($nombre);
        $usuario = trim($usuario);
        $rol = trim($rol);

        // Validar los campos obligatorios
        if (
            empty($nombre) ||
            empty($usuario) ||
            empty($password) ||
            empty($rol)
        ) {
            throw new \InvalidArgumentException(
                "Todos los campos son obligatorios."
            );
        }

        // Validar el rol seleccionado
        if (!in_array($rol, ['Administrador', 'Empleado'], true)) {
            throw new \InvalidArgumentException(
                "El tipo de cuenta seleccionado no es válido."
            );
        }

        // Validar la longitud del nombre
        if (strlen($nombre) > 100) {
            throw new \InvalidArgumentException(
                "El nombre no puede superar los 100 caracteres."
            );
        }

        // Validar el nombre de usuario
        if (
            strlen($usuario) < 3 ||
            strlen($usuario) > 50
        ) {
            throw new \InvalidArgumentException(
                "El usuario debe tener entre 3 y 50 caracteres."
            );
        }

        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $usuario)) {
            throw new \InvalidArgumentException(
                "El usuario contiene caracteres no permitidos."
            );
        }

        // Validar la contraseña
        if (strlen($password) < 12) {
            throw new \InvalidArgumentException(
                "La contraseña debe tener al menos 12 caracteres."
            );
        }

        // Comprobar que el usuario no esté registrado
        if ($this->usuarioDAO->existeUsuario($usuario)) {
            throw new \InvalidArgumentException(
                "El nombre de usuario ya está registrado."
            );
        }

        // Generar el hash de la contraseña
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Crear el objeto Usuario
        $nuevoUsuario = new Usuario(
            null,
            $nombre,
            $usuario,
            $passwordHash,
            $rol,
            1
        );

        return $this->usuarioDAO->guardar($nuevoUsuario);
    }


    // Buscar un usuario por su ID
    public function obtenerUsuario(int $id): ?Usuario
    {
        if ($id <= 0) {
            return null;
        }

        return $this->usuarioDAO->buscarPorId($id);
    }

    // Listar todos los usuarios
    public function listarUsuarios(): array
    {
        return $this->usuarioDAO->listar();
    }

    // Actualizar los datos de un empleado
    public function actualizarEmpleado(
        int $id,
        string $nombre,
        string $usuario,
        ?string $password = null
    ): bool {

        $nombre = trim($nombre);
        $usuario = trim($usuario);

        if ($id <= 0 || empty($nombre) || empty($usuario)) {
            throw new \InvalidArgumentException(
                "Los datos del empleado son obligatorios."
            );
        }

        if (strlen($nombre) > 100) {
            throw new \InvalidArgumentException(
                "El nombre no puede superar los 100 caracteres."
            );
        }

        if (
            strlen($usuario) < 3 ||
            strlen($usuario) > 50 ||
            !preg_match('/^[a-zA-Z0-9_.-]+$/', $usuario)
        ) {
            throw new \InvalidArgumentException(
                "El nombre de usuario no es válido."
            );
        }

        // Buscar el empleado
        $empleado = $this->usuarioDAO->buscarPorId($id);

        if ($empleado === null) {
            throw new \InvalidArgumentException(
                "El usuario no existe."
            );
        }

        // Evitar modificar cuentas de administrador
        if ($empleado->getRol() !== 'Empleado') {
            throw new \InvalidArgumentException(
                "No se puede modificar esta cuenta desde el módulo de empleados."
            );
        }

        // Comprobar si el nombre de usuario pertenece a otra cuenta
        if (
            $empleado->getUsuario() !== $usuario &&
            $this->usuarioDAO->existeUsuario($usuario)
        ) {
            throw new \InvalidArgumentException(
                "El nombre de usuario ya está registrado."
            );
        }

        // Si se proporciona una nueva contraseña, generar su hash
        if ($password !== null && $password !== '') {

            if (strlen($password) < 12) {
                throw new \InvalidArgumentException(
                    "La contraseña debe tener al menos 12 caracteres."
                );
            }

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

        } else {

            // Conservar la contraseña actual
            $passwordHash = $empleado->getPasswordHash();
        }

        // Actualizar el objeto
        $empleado->setNombre($nombre);
        $empleado->setUsuario($usuario);
        $empleado->setPasswordHash($passwordHash);

        return $this->usuarioDAO->actualizar($empleado);
    }

    // Activar o desactivar una cuenta de empleado
    public function cambiarEstado(int $id, int $estado): bool
    {
        if ($id <= 0 || !in_array($estado, [0, 1], true)) {
            throw new \InvalidArgumentException(
                "Los datos para cambiar el estado no son válidos."
            );
        }

        $empleado = $this->usuarioDAO->buscarPorId($id);

        if ($empleado === null) {
            throw new \InvalidArgumentException(
                "El usuario no existe."
            );
        }

        if ($empleado->getRol() !== 'Empleado') {
            throw new \InvalidArgumentException(
                "No se puede modificar el estado de esta cuenta desde el módulo de empleados."
            );
        }

        return $this->usuarioDAO->cambiarEstado($id, $estado);
    }

    // Autenticar un usuario para iniciar sesión
    public function autenticar(
        string $usuario,
        string $password
    ): ?Usuario {

        $usuario = trim($usuario);

        if (empty($usuario) || empty($password)) {
            return null;
        }

        // Buscar la cuenta
        $cuenta = $this->usuarioDAO->buscarPorUsuario($usuario);

        if ($cuenta === null) {
            return null;
        }

        // Comprobar que la cuenta esté activa
        if ($cuenta->getEstado() !== 1) {
            return null;
        }

        // Verificar la contraseña
        if (!password_verify(
            $password,
            $cuenta->getPasswordHash()
        )) {
            return null;
        }

        return $cuenta;
    }
}
