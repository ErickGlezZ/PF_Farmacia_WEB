<?php

namespace App\Services;

use App\Models\Paciente;
use App\Interfaces\PacienteRepositoryInterface;

class PacienteService {

    private $pacienteRepository;

    public function __construct(PacienteRepositoryInterface $pacienteRepository) {
        $this->pacienteRepository = $pacienteRepository;
    }

    // Registrar paciente
    public function registrarPaciente(Paciente $paciente) {
        $this->validarPaciente($paciente);
        return $this->pacienteRepository->guardar($paciente);
    }

    // Consultar paciente por ID
    public function consultarPaciente($id) {

        if (!is_numeric($id) || (int) $id <= 0 || (string)(int)$id !== (string)$id) {
            throw new \InvalidArgumentException("El ID del paciente no es válido.");
        }

        return $this->pacienteRepository->buscarPorId($id);
    }

    // Listar pacientes
    public function listarPacientes() {
        return $this->pacienteRepository->listar();
    }

    // Actualizar paciente
    public function actualizarPaciente(Paciente $paciente) {

        $id = $paciente->getIdPaciente();

        if (!is_numeric($id) || (int) $id <= 0) {
            throw new \InvalidArgumentException("El ID del paciente es obligatorio.");
        }

        $this->validarPaciente($paciente);

        return $this->pacienteRepository->actualizar($paciente);
    }

    // Eliminar paciente
    public function eliminarPaciente($id) {

        if (!is_numeric($id) || (int) $id <= 0 || (string)(int)$id !== (string)$id) {
            throw new \InvalidArgumentException("El ID del paciente no es válido.");
        }

        return $this->pacienteRepository->eliminar($id);
    }

    // Buscar pacientes por filtros
    public function filtrarPacientes($filtros) {
        return $this->pacienteRepository->buscarPorFiltros($filtros);
    }

    // Validar información del paciente
    private function validarPaciente(Paciente $paciente) {

        $campos = [
            'SSN' => $paciente->getSSN(),
            'Nombre' => $paciente->getNombre(),
            'Primer_Ape' => $paciente->getPrimerApe(),
            'Segundo_Ape' => $paciente->getSegundoApe(),
            'Calle' => $paciente->getCalle(),
            'Numero' => $paciente->getNumero(),
            'Colonia' => $paciente->getColonia(),
            'Codigo_Postal' => $paciente->getCodigoPostal()
        ];

        foreach ($campos as $nombre => $valor) {

            if (!is_string($valor) || trim($valor) === '') {
                throw new \InvalidArgumentException("El campo $nombre es obligatorio.");
            }
        }

        // Validar SSN
        if (strlen($paciente->getSSN()) !== 11) {
            throw new \InvalidArgumentException("El SSN debe tener 11 caracteres.");
        }

        // Validar edad
        $edad = $paciente->getEdad();

        if (!is_numeric($edad) || (int)$edad <= 0 || (int)$edad != $edad) {
            throw new \InvalidArgumentException("La edad debe ser un número entero mayor a cero.");
        }

        // Validar médico de cabecera (opcional)
        $idMedico = $paciente->getIdMedicoCabecera();

        if ($idMedico !== null && $idMedico !== '') {

            if (!is_numeric($idMedico) || (int)$idMedico <= 0 || (int)$idMedico != $idMedico) {
                throw new \InvalidArgumentException("El médico de cabecera seleccionado no es válido.");
            }
        }

        // Validar código postal
        if (!preg_match('/^[0-9]{5}$/', $paciente->getCodigoPostal())) {
            throw new \InvalidArgumentException("El código postal debe contener exactamente 5 dígitos.");
        }

        // Validar longitudes máximas
        $longitudes = [
            'Nombre' => 50,
            'Primer_Ape' => 50,
            'Segundo_Ape' => 50,
            'Calle' => 100,
            'Numero' => 10,
            'Colonia' => 50
        ];

        foreach ($longitudes as $campo => $maximo) {

            if (strlen($campos[$campo]) > $maximo) {
                throw new \InvalidArgumentException("El campo $campo no debe superar los $maximo caracteres.");
            }
        }
    }
}
