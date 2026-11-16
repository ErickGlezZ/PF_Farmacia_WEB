<?php

namespace App\Config;

use PDO;
use PDOException;

class Conexion {

    private static $instancia = null;

    
    private $conexion;

    
    private $host = "localhost:3306";
    private $dbname = "bd_farmacia_web";
    private $usuario = "Erick";
    private $password = "usuarioitsj";

    private function __construct() {

        try {

            $this->conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->usuario,
                $this->password
            );

            $this->conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->conexion->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            $this->conexion->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );

            if ($this->conexion) {
                echo "Conexión exitosa a la base de datos.";
            }else {
                echo "Error al conectar con la base de datos.";
            }

        } catch (PDOException $e) {

            throw new PDOException(
                "Error al conectar con la base de datos."
            );

        }

    }

    // Obtener la instancia única
    public static function getInstance() {

        if (self::$instancia === null) {
            self::$instancia = new Conexion();
        }

        return self::$instancia;
    }

    // Obtener la conexión PDO
    public function getConnection() {

        return $this->conexion;
    }

}
