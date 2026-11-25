<?php

namespace App\dao;

use PDO;

class RecuperacionPasswordDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registrar una recuperación de contraseña.
     */
    public function registrar(
        int $idUsuario,
        string $jtiHash,
        int $expiracion
    ): bool {

        $sql = "INSERT INTO recuperaciones_password
                (id_usuario, jti_hash, fecha_expiracion)
                VALUES (:id_usuario, :jti_hash, :fecha_expiracion)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id_usuario' => $idUsuario,
            ':jti_hash' => $jtiHash,
            ':fecha_expiracion' => date('Y-m-d H:i:s', $expiracion)
        ]);
    }

    /**
     * Consultar un token de recuperación vigente.
     */
    public function consultarVigente(string $jtiHash): ?array
    {
        $sql = "SELECT id_recuperacion, id_usuario
                FROM recuperaciones_password
                WHERE jti_hash = :jti_hash
                  AND usado = 0
                  AND fecha_expiracion > NOW()
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ':jti_hash' => $jtiHash
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

    
    /**
     * Cambiar contraseña y consumir el token.
     */
    public function cambiarPassword(
        string $jtiHash,
        int $idUsuario,
        string $passwordHash
    ): bool {

        try {

            $this->conexion->beginTransaction();

            $sql = "SELECT id_recuperacion
                    FROM recuperaciones_password
                    WHERE jti_hash = :jti_hash
                    AND id_usuario = :id_usuario
                    AND usado = 0
                    AND fecha_expiracion > NOW()
                    FOR UPDATE";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                ':jti_hash' => $jtiHash,
                ':id_usuario' => $idUsuario
            ]);

            $recuperacion = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$recuperacion) {
                $this->conexion->rollBack();
                return false;
            }

            $sql = "UPDATE usuarios
                    SET password_hash = :password_hash
                    WHERE id_usuario = :id_usuario
                    AND estado = 1";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                ':password_hash' => $passwordHash,
                ':id_usuario' => $idUsuario
            ]);

            if ($stmt->rowCount() !== 1) {
                $this->conexion->rollBack();
                return false;
            }

            $sql = "UPDATE recuperaciones_password
                    SET usado = 1,
                        fecha_uso = NOW()
                    WHERE id_recuperacion = :id_recuperacion";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                ':id_recuperacion' => $recuperacion['id_recuperacion']
            ]);

            $this->conexion->commit();

            return true;

        } catch (\Throwable $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

}
