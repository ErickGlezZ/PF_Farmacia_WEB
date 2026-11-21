<?php

namespace App\Services;

use App\Models\Medico;
use App\Interfaces\MedicoRepositoryInterface;

class MedicoService {

    private $medicoRepository;

    public function __construct(MedicoRepositoryInterface $medicoRepository) {
        $this->medicoRepository = $medicoRepository;
    }

    // Registrar médico
    public function registrarMedico(Medico $medico) {

        $this->validarMedico($medico);

        return $this->medicoRepository->guardar($medico);
    }

    // Consultar médico por ID
    public function consultarMedico($id) {

        if ($id <= 0) {
            throw new \InvalidArgumentException("El ID no es válido.");
        }

        return $this->medicoRepository->buscarPorId($id);
    }

    // Listar médicos
    public function listarMedicos() {

        return $this->medicoRepository->listar();
    }

    // Actualizar médico
    public function actualizarMedico(Medico $medico) {

        if ($medico->getIdMedico() <= 0) {
            throw new \InvalidArgumentException("El ID del médico es obligatorio.");
        }

        $this->validarMedico($medico);

        return $this->medicoRepository->actualizar($medico);
    }

    // Eliminar médico
    public function eliminarMedico($id) {

        if ($id <= 0) {
            throw new \InvalidArgumentException("El ID no es válido.");
        }

        return $this->medicoRepository->eliminar($id);
    }

    // Buscar médicos por filtros
    public function filtrarMedicos($filtros) {

        return $this->medicoRepository->buscarPorFiltros($filtros);
    }

    // Validar información del médico
    private function validarMedico(Medico $medico) {

        $campos = [
            'SSN' => $medico->getSSN(),
            'Nombre' => $medico->getNombre(),
            'Primer_Ap' => $medico->getPrimerAp(),
            'Segundo_Ape' => $medico->getSegundoApe(),
            'Especialidad' => $medico->getEspecialidad()
        ];

        foreach ($campos as $nombre => $valor) {

            if (!is_string($valor) || trim($valor) === '') {
                throw new \InvalidArgumentException("El campo $nombre es obligatorio.");
            }

        }

        if (strlen($medico->getSSN()) !== 11) {
            throw new \InvalidArgumentException("El SSN debe tener 11 caracteres.");
        }

        $experiencia = $medico->getAñosExperiencia();

        if (!is_numeric($experiencia) || (int) $experiencia <= 0) {
            throw new \InvalidArgumentException("Los años de experiencia deben ser mayores a cero.");
        }

        if ((int) $experiencia != $experiencia) {
            throw new \InvalidArgumentException("Los años de experiencia deben ser un número entero.");
        }

        foreach (['Nombre', 'Primer_Ap', 'Segundo_Ape', 'Especialidad'] as $campo) {

            if (strlen($campos[$campo]) > 50) {
                throw new \InvalidArgumentException("El campo $campo no debe superar los 50 caracteres.");
            }

        }

    }

}
