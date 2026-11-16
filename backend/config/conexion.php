
<?php

namespace App\config;

use PDO;
use PDOException;

class Database {

    private static $instancia = null;

    
    private $conexion;

    
    private $host = "localhost";
    private $dbname = "farmacias_web";
    private $usuario = "root";
    private $password = "";

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

        } catch (PDOException $e) {

            throw new PDOException(
                "Error al conectar con la base de datos."
            );

        }

    }

    // Obtener la instancia única
    public static function getInstance() {

        if (self::$instancia === null) {
            self::$instancia = new Database();
        }

        return self::$instancia;
    }

    // Obtener la conexión PDO
    public function getConnection() {

        return $this->conexion;
    }

}
