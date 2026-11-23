<?php

namespace App\Services;

use App\Models\Receta;
use App\Interfaces\RecetaRepositoryInterface;

class RecetaService {

    private $recetaRepository;

    public function __construct(RecetaRepositoryInterface $recetaRepository) {
        $this->recetaRepository = $recetaRepository;
    }

    // Registrar receta
    public function registrarReceta(Receta $receta) {
        $this->validarReceta($receta);
        return $this->recetaRepository->guardar($receta);
    }

    // Consultar receta por ID
    public function consultarReceta($id) {

        if (!is_numeric($id) || (int)$id <= 0 || (string)(int)$id !== (string)$id) {
            throw new \InvalidArgumentException("El ID de la receta no es válido.");
        }

        return $this->recetaRepository->buscarPorId($id);
    }

    // Listar recetas
    public function listarRecetas() {
        return $this->recetaRepository->listar();
    }

    // Actualizar receta
    public function actualizarReceta(Receta $receta) {

        $id = $receta->getIdReceta();

        if (!is_numeric($id) || (int)$id <= 0) {
            throw new \InvalidArgumentException("El ID de la receta es obligatorio.");
        }

        $this->validarReceta($receta);

        return $this->recetaRepository->actualizar($receta);
    }

    // Eliminar receta
    public function eliminarReceta($id) {

        if (!is_numeric($id) || (int)$id <= 0 || (string)(int)$id !== (string)$id) {
            throw new \InvalidArgumentException("El ID de la receta no es válido.");
        }

        return $this->recetaRepository->eliminar($id);
    }

    // Buscar recetas por filtros
    public function filtrarRecetas($filtros) {
        return $this->recetaRepository->buscarPorFiltros($filtros);
    }

    // Validar información de la receta
    private function validarReceta(Receta $receta) {

        $campos = [
            'Medicamento' => $receta->getMedicamento(),
            'Unidad' => $receta->getUnidad(),
            'Indicaciones' => $receta->getIndicaciones()
        ];

        foreach ($campos as $nombre => $valor) {

            if (!is_string($valor) || trim($valor) === '') {
                throw new \InvalidArgumentException("El campo $nombre es obligatorio.");
            }
        }

        // Validar médico
        $idMedico = $receta->getIdMedico();

        if (!is_numeric($idMedico) || (int)$idMedico <= 0 || (int)$idMedico != $idMedico) {
            throw new \InvalidArgumentException("El médico seleccionado no es válido.");
        }

        // Validar paciente
        $idPaciente = $receta->getIdPaciente();

        if (!is_numeric($idPaciente) || (int)$idPaciente <= 0 || (int)$idPaciente != $idPaciente) {
            throw new \InvalidArgumentException("El paciente seleccionado no es válido.");
        }

        // Validar cantidad
        $cantidad = $receta->getCantidad();

        if (!is_numeric($cantidad) || (int)$cantidad <= 0 || (int)$cantidad != $cantidad) {
            throw new \InvalidArgumentException("La cantidad debe ser un número entero mayor a cero.");
        }

        // Validar fecha
        $fecha = $receta->getFecha();

        $fechaValida = \DateTime::createFromFormat('!Y-m-d', $fecha);

        if (
            !$fechaValida ||
            $fechaValida->format('Y-m-d') !== $fecha
        ) {
            throw new \InvalidArgumentException("La fecha de la receta no es válida.");
        }

        // Validar longitudes máximas
        $longitudes = [
            'Medicamento' => 100,
            'Unidad' => 20,
            'Indicaciones' => 100
        ];

        foreach ($longitudes as $campo => $maximo) {

            if (strlen($campos[$campo]) > $maximo) {
                throw new \InvalidArgumentException("El campo $campo no debe superar los $maximo caracteres.");
            }
        }
    }
}
