<?php

namespace App\Controllers;

use App\Services\PacienteService;
use App\Models\Paciente;

class PacienteController {

    private $pacienteService;

    public function __construct(PacienteService $pacienteService) {
        $this->pacienteService = $pacienteService;
    }

    // Registrar paciente
    public function registrar($datos) {

        $paciente = new Paciente(
            $datos['SSN'],
            $datos['Nombre'],
            $datos['Primer_Ape'],
            $datos['Segundo_Ape'],
            $datos['Edad'],
            !empty($datos['id_Medico_Cabecera'])
                ? $datos['id_Medico_Cabecera']
                : null,
            $datos['Calle'],
            $datos['Numero'],
            $datos['Colonia'],
            $datos['Codigo_Postal']
        );

        return $this->pacienteService->registrarPaciente($paciente);
    }

    // Eliminar paciente
    public function eliminar($id) {
        return $this->pacienteService->eliminarPaciente($id);
    }

    // Actualizar paciente
    public function actualizar($datos) {

        $paciente = new Paciente(
            $datos['SSN'],
            $datos['Nombre'],
            $datos['Primer_Ape'],
            $datos['Segundo_Ape'],
            $datos['Edad'],
            !empty($datos['id_Medico_Cabecera'])
                ? $datos['id_Medico_Cabecera']
                : null,
            $datos['Calle'],
            $datos['Numero'],
            $datos['Colonia'],
            $datos['Codigo_Postal'],
            $datos['id_Paciente']
        );

        return $this->pacienteService->actualizarPaciente($paciente);
    }

    // Consultar paciente por ID
    public function consultar($id) {
        return $this->pacienteService->consultarPaciente($id);
    }

    // Listar pacientes
    public function listar() {
        return $this->pacienteService->listarPacientes();
    }

    // Filtrar pacientes
    public function filtrar($filtros) {
        return $this->pacienteService->filtrarPacientes($filtros);
    }
}
