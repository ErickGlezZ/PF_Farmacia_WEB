<?php

namespace App\facade;

use App\Config\Conexion;
use App\DAO\MedicoDAO;
use App\DAO\PacienteDAO;
use App\Services\MedicoService;
use App\Services\PacienteService;
use App\Controllers\MedicoController;
use App\Controllers\PacienteController;

class FarmaciaFacade {

    private $medicoController;
    private $pacienteController;

    public function __construct() {

        // Obtener conexión a la base de datos
        $conexionBD = Conexion::getInstance();
        $conexion = $conexionBD->getConnection();

        // Configurar módulo de Médicos
        $medicoDAO = new MedicoDAO($conexion);
        $medicoService = new MedicoService($medicoDAO);
        $this->medicoController = new MedicoController($medicoService);

        // Configurar módulo de Pacientes
        $pacienteDAO = new PacienteDAO($conexion);
        $pacienteService = new PacienteService($pacienteDAO);
        $this->pacienteController = new PacienteController($pacienteService);
    }

    // =====================================
    // MÉTODOS DEL MÓDULO DE MÉDICOS
    // =====================================

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

    // =====================================
    // MÉTODOS DEL MÓDULO DE PACIENTES
    // =====================================

    public function registrarPaciente($datos) {
        return $this->pacienteController->registrar($datos);
    }

    public function consultarPaciente($id) {
        return $this->pacienteController->consultar($id);
    }

    public function listarPacientes() {
        return $this->pacienteController->listar();
    }

    public function actualizarPaciente($datos) {
        return $this->pacienteController->actualizar($datos);
    }

    public function eliminarPaciente($id) {
        return $this->pacienteController->eliminar($id);
    }

    public function filtrarPacientes($filtros) {
        return $this->pacienteController->filtrar($filtros);
    }
}
