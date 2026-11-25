<?php

namespace App\facade;

use App\Config\Conexion;

use App\DAO\MedicoDAO;
use App\DAO\PacienteDAO;
use App\DAO\RecetaDAO;
use App\DAO\UsuarioDAO;
use App\DAO\RecuperacionPasswordDAO;

use App\Services\MedicoService;
use App\Services\PacienteService;
use App\Services\RecetaService;
use App\Services\UsuarioService;
use App\Services\RecuperacionPasswordService;

use App\Controllers\MedicoController;
use App\Controllers\PacienteController;
use App\Controllers\RecetaController;
use App\Controllers\UsuarioController;
use App\Controllers\RecuperacionPasswordController;

class FarmaciaFacade {

    private $medicoController;
    private $pacienteController;
    private $recetaController;
    private $usuarioController;
    private $recuperacionPasswordController;

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

        // Configurar módulo de Recetas
        $recetaDAO = new RecetaDAO($conexion);
        $recetaService = new RecetaService($recetaDAO);
        $this->recetaController = new RecetaController($recetaService);

        // Configurar módulo de Usuarios
        $usuarioDAO = new UsuarioDAO($conexion);
        $usuarioService = new UsuarioService($usuarioDAO);
        $this->usuarioController = new UsuarioController($usuarioService);

        // Configurar módulo de recuperación de contraseñas
        $recuperacionDAO = new RecuperacionPasswordDAO($conexion);

        $recuperacionService = new RecuperacionPasswordService(
            $usuarioDAO,
            $recuperacionDAO
        );

        $this->recuperacionPasswordController =
            new RecuperacionPasswordController($recuperacionService);
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

    // =====================================
    // MÉTODOS DEL MÓDULO DE RECETAS
    // =====================================

    public function registrarReceta($datos) {
        return $this->recetaController->registrar($datos);
    }

    public function consultarReceta($id) {
        return $this->recetaController->consultar($id);
    }

    public function listarRecetas() {
        return $this->recetaController->listar();
    }

    public function actualizarReceta($datos) {
        return $this->recetaController->actualizar($datos);
    }

    public function eliminarReceta($id) {
        return $this->recetaController->eliminar($id);
    }

    public function filtrarRecetas($filtros) {
        return $this->recetaController->filtrar($filtros);
    }

    public function contarRecetasPaciente($idPaciente) {
        return $this->recetaController->contarPorPaciente($idPaciente);
    }

    
    // =====================================
    // MÉTODOS DEL MÓDULO DE USUARIOS
    // =====================================

    public function registrarUsuario($nombre, $usuario, $password, $rol) {
        return $this->usuarioController->registrar(
            $nombre,
            $usuario,
            $password,
            $rol
        );
    }

    public function consultarUsuario($id) {
        return $this->usuarioController->consultar($id);
    }

    public function listarUsuarios() {
        return $this->usuarioController->listar();
    }

    public function actualizarEmpleado($id, $nombre, $usuario, $password = null) {
        return $this->usuarioController->actualizar(
            $id,
            $nombre,
            $usuario,
            $password
        );
    }

    public function cambiarEstadoUsuario($id, $estado) {
        return $this->usuarioController->cambiarEstado(
            $id,
            $estado
        );
    }

    public function autenticarUsuario($usuario, $password) {
        return $this->usuarioController->autenticar(
            $usuario,
            $password
        );
    }

    
    // =====================================
    // MÉTODOS DEL MÓDULO DE RECUPERACIÓN
    // =====================================

    public function solicitarRecuperacionPassword($usuario) {
        return $this->recuperacionPasswordController->solicitar($usuario);
    }

    public function validarTokenRecuperacion($token) {
        return $this->recuperacionPasswordController->validarToken($token);
    }

    public function restablecerPassword($token, $password, $confirmacion) {
        return $this->recuperacionPasswordController->restablecer(
            $token,
            $password,
            $confirmacion
        );
    }

    
}
