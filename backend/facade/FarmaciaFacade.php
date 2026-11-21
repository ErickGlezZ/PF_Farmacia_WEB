<?php

namespace App\facade;

use App\config\conexion;
use App\DAO\MedicoDAO;
use App\Services\MedicoService;
use App\Controllers\MedicoController;

class FarmaciaFacade {

    private $medicoController;

    public function __construct() {

        // Obtener la conexión a la base de datos
        $conexionBD = conexion::getInstance();
        $conexion = $conexionBD->getConnection();

        // Construir las capas de médicos
        $medicoDAO = new MedicoDAO($conexion);
        $medicoService = new MedicoService($medicoDAO);
        $this->medicoController = new MedicoController($medicoService);
    }

    // =========================
    // CRUD DE MÉDICOS
    // =========================

    public function registrarMedico($datos) {
        return $this->medicoController->registrar($datos);
    }

    public function consultarMedico($id) {
        return $this->medicoController->consultar($id);
    }

    public function listarMedicos() {
        return $this->medicoController->listar();
    }

    public function actualizarMedico($datos) {
        return $this->medicoController->actualizar($datos);
    }

    public function eliminarMedico($id) {
        return $this->medicoController->eliminar($id);
    }

    public function filtrarMedicos($filtros) {
        return $this->medicoController->filtrar($filtros);
    }
}
