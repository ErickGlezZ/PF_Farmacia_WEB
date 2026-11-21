<?php

namespace App\Controllers;

use App\Services\MedicoService;
use App\Models\Medico;

class MedicoController {

    private $medicoService;

    public function __construct(MedicoService $medicoService) {
        $this->medicoService = $medicoService;
    }

    public function registrar($datos) {

        $medico = new Medico(
            $datos['SSN'],
            $datos['Nombre'],
            $datos['Primer_Ap'],
            $datos['Segundo_Ape'],
            $datos['Especialidad'],
            $datos['Años_Experiencia']
        );

        return $this->medicoService->registrarMedico($medico);
    }

    public function eliminar($id) {
        return $this->medicoService->eliminarMedico($id);
    }

    public function actualizar($datos) {

        $medico = new Medico(
            $datos['SSN'],
            $datos['Nombre'],
            $datos['Primer_Ap'],
            $datos['Segundo_Ape'],
            $datos['Especialidad'],
            $datos['Años_Experiencia'],
            $datos['id_Medico']
        );

        return $this->medicoService->actualizarMedico($medico);
    }

    public function consultar($id) {
        return $this->medicoService->consultarMedico($id);
    }

    public function listar() {
        return $this->medicoService->listarMedicos();
    }

    public function filtrar($filtros) {
        return $this->medicoService->filtrarMedicos($filtros);
    }

}
