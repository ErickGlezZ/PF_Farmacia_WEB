<?php

namespace App\DAO;

use PDO;
use App\Models\Usuario;
use App\Interfaces\UsuarioRepositoryInterface;

class UsuarioDAO implements UsuarioRepositoryInterface
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    // Registrar un usuario
    public function guardar(Usuario $usuario): bool
    {
        $sql = "INSERT INTO usuarios
                (nombre, usuario, password_hash, rol, estado)
                VALUES
                (:nombre, :usuario, :password_hash, :rol, :estado)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre' => $usuario->getNombre(),
            ':usuario' => $usuario->getUsuario(),
            ':password_hash' => $usuario->getPasswordHash(),
            ':rol' => $usuario->getRol(),
            ':estado' => $usuario->getEstado()
        ]);
    }

    // Buscar un usuario por su ID
    public function buscarPorId(int $id): ?Usuario
    {
        $sql = "SELECT * FROM usuarios
                WHERE id_usuario = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return $this->convertirModelo($fila);
    }

    // Buscar un usuario por su nombre de acceso
    public function buscarPorUsuario(string $usuario): ?Usuario
    {
        $sql = "SELECT * FROM usuarios
                WHERE usuario = :usuario";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':usuario' => $usuario]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return $this->convertirModelo($fila);
    }

    // Listar todos los usuarios
    public function listar(): array
    {
        $sql = "SELECT * FROM usuarios
                ORDER BY id_usuario DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $usuarios = [];

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $this->convertirModelo($fila);
        }

        return $usuarios;
    }

    // Actualizar los datos de un usuario
    public function actualizar(Usuario $usuario): bool
    {
        $sql = "UPDATE usuarios
                SET nombre = :nombre,
                    usuario = :usuario,
                    password_hash = :password_hash,
                    rol = :rol
                WHERE id_usuario = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre' => $usuario->getNombre(),
            ':usuario' => $usuario->getUsuario(),
            ':password_hash' => $usuario->getPasswordHash(),
            ':rol' => $usuario->getRol(),
            ':id' => $usuario->getIdUsuario()
        ]);
    }

    // Cambiar el estado de una cuenta
    public function cambiarEstado(int $id, int $estado): bool
    {
        $transaccionIniciada = false;

        try {

            if (!$this->conexion->inTransaction()) {
                $this->conexion->beginTransaction();
                $transaccionIniciada = true;
            }

            // Consultar el usuario que se desea modificar
            $sql = "SELECT id_usuario, rol, estado
                    FROM usuarios
                    WHERE id_usuario = :id
                    FOR UPDATE";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                if ($transaccionIniciada) {
                    $this->conexion->commit();
                }

                return false;
            }

            // Evitar desactivar al último administrador activo
            if (
                $usuario['rol'] === 'Administrador' &&
                (int) $usuario['estado'] === 1 &&
                $estado === 0
            ) {

                $sql = "SELECT COUNT(*)
                        FROM usuarios
                        WHERE rol = 'Administrador'
                        AND estado = 1
                        FOR UPDATE";

                $stmt = $this->conexion->prepare($sql);
                $stmt->execute();

                $administradoresActivos = (int) $stmt->fetchColumn();

                if ($administradoresActivos <= 1) {

                    if ($transaccionIniciada) {
                        $this->conexion->commit();
                    }

                    return false;
                }
            }

            // Actualizar el estado
            $sql = "UPDATE usuarios
                    SET estado = :estado
                    WHERE id_usuario = :id";

            $stmt = $this->conexion->prepare($sql);

            $resultado = $stmt->execute([
                ':estado' => $estado,
                ':id' => $id
            ]);

            if ($transaccionIniciada) {
                $this->conexion->commit();
            }

            return $resultado;

        } catch (\Throwable $e) {

            if (
                $transaccionIniciada &&
                $this->conexion->inTransaction()
            ) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

    // Comprobar si un nombre de usuario ya existe
    public function existeUsuario(string $usuario): bool
    {
        $sql = "SELECT COUNT(*)
                FROM usuarios
                WHERE usuario = :usuario";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':usuario' => $usuario]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Convertir un registro de MySQL en un objeto Usuario
    private function convertirModelo(array $fila): Usuario
    {
        return new Usuario(
            (int) $fila['id_usuario'],
            $fila['nombre'],
            $fila['usuario'],
            $fila['password_hash'],
            $fila['rol'],
            (int) $fila['estado'],
            $fila['fecha_creacion']
        );
    }
}
