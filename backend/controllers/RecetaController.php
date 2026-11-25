<?php

namespace App\Controllers;

use App\Services\RecetaService;
use App\Models\Receta;

class RecetaController {

    private $recetaService;

    public function __construct(RecetaService $recetaService) {
        $this->recetaService = $recetaService;
    }

    // Registrar receta
    public function registrar($datos) {

        $receta = new Receta(
            $datos['id_Medico'],
            $datos['id_Paciente'],
            $datos['Medicamento'],
            $datos['Fecha'],
            $datos['Cantidad'],
            $datos['Unidad'],
            $datos['Indicaciones']
        );

        return $this->recetaService->registrarReceta($receta);
    }

    // Eliminar receta
    public function eliminar($id) {
        return $this->recetaService->eliminarReceta($id);
    }

    // Actualizar receta
    public function actualizar($datos) {

        $receta = new Receta(
            $datos['id_Medico'],
            $datos['id_Paciente'],
            $datos['Medicamento'],
            $datos['Fecha'],
            $datos['Cantidad'],
            $datos['Unidad'],
            $datos['Indicaciones'],
            $datos['ID_Receta']
        );

        return $this->recetaService->actualizarReceta($receta);
    }

    // Consultar receta por ID
    public function consultar($id) {
        return $this->recetaService->consultarReceta($id);
    }

    // Listar recetas
    public function listar() {
        return $this->recetaService->listarRecetas();
    }

    // Filtrar recetas
    public function filtrar($filtros) {
        return $this->recetaService->filtrarRecetas($filtros);
    }

    // Contar recetas relacionadas con un paciente
    public function contarPorPaciente($idPaciente) {
        return $this->recetaService->contarPorPaciente($idPaciente);
    }
}
