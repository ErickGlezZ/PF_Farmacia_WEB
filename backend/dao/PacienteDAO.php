<?php

namespace App\DAO;

use PDO;
use App\Models\Paciente;
use App\Interfaces\PacienteRepositoryInterface;

class PacienteDAO implements PacienteRepositoryInterface {

    private $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // Registrar paciente
    public function guardar($paciente) {

        $sql = "INSERT INTO pacientes
                (SSN, Nombre, Primer_Ape, Segundo_Ape, Edad,
                id_Medico_Cabecera, Calle, Numero, Colonia, Codigo_Postal)
                VALUES
                (:ssn, :nombre, :primer_ap, :segundo_ape, :edad,
                :medico, :calle, :numero, :colonia, :codigo_postal)";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':ssn' => $paciente->getSSN(),
            ':nombre' => $paciente->getNombre(),
            ':primer_ap' => $paciente->getPrimerApe(),
            ':segundo_ape' => $paciente->getSegundoApe(),
            ':edad' => $paciente->getEdad(),
            ':medico' => $paciente->getIdMedicoCabecera(),
            ':calle' => $paciente->getCalle(),
            ':numero' => $paciente->getNumero(),
            ':colonia' => $paciente->getColonia(),
            ':codigo_postal' => $paciente->getCodigoPostal()
        ]);

        $id = $this->conexion->lastInsertId();
        $paciente->setIdPaciente($id);

        return $paciente;
    }

    // Buscar paciente por ID
    public function buscarPorId($id) {

        $sql = "SELECT * FROM pacientes
                WHERE id_Paciente = :id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':id' => $id]);

        $fila = $consulta->fetch();

        if (!$fila) {
            return null;
        }

        return $this->crearObjetoPaciente($fila);
    }

    // Listar pacientes
    public function listar() {

        $sql = "SELECT * FROM pacientes
                ORDER BY id_Paciente ASC";

        $consulta = $this->conexion->query($sql);
        $filas = $consulta->fetchAll();

        $pacientes = [];

        foreach ($filas as $fila) {
            $pacientes[] = $this->crearObjetoPaciente($fila);
        }

        return $pacientes;
    }

    // Actualizar paciente
    public function actualizar($paciente) {

        $sql = "UPDATE pacientes SET
                SSN = :ssn,
                Nombre = :nombre,
                Primer_Ape = :primer_ap,
                Segundo_Ape = :segundo_ape,
                Edad = :edad,
                id_Medico_Cabecera = :medico,
                Calle = :calle,
                Numero = :numero,
                Colonia = :colonia,
                Codigo_Postal = :codigo_postal
                WHERE id_Paciente = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':ssn' => $paciente->getSSN(),
            ':nombre' => $paciente->getNombre(),
            ':primer_ap' => $paciente->getPrimerApe(),
            ':segundo_ape' => $paciente->getSegundoApe(),
            ':edad' => $paciente->getEdad(),
            ':medico' => $paciente->getIdMedicoCabecera(),
            ':calle' => $paciente->getCalle(),
            ':numero' => $paciente->getNumero(),
            ':colonia' => $paciente->getColonia(),
            ':codigo_postal' => $paciente->getCodigoPostal(),
            ':id' => $paciente->getIdPaciente()
        ]);
    }

    // Eliminar paciente
    public function eliminar($id) {

        $sql = "DELETE FROM pacientes
                WHERE id_Paciente = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([':id' => $id]);
    }

    // Buscar pacientes por filtros
    public function buscarPorFiltros($filtros) {

        $sql = "SELECT * FROM pacientes WHERE 1 = 1";
        $parametros = [];

        if (!empty($filtros['SSN'])) {
            $sql .= " AND SSN LIKE :ssn";
            $parametros[':ssn'] = '%' . $filtros['SSN'] . '%';
        }

        if (!empty($filtros['Nombre'])) {
            $sql .= " AND Nombre LIKE :nombre";
            $parametros[':nombre'] = '%' . $filtros['Nombre'] . '%';
        }

        if (!empty($filtros['Primer_Ape'])) {
            $sql .= " AND Primer_Ape LIKE :primer_ap";
            $parametros[':primer_ap'] = '%' . $filtros['Primer_Ape'] . '%';
        }

        if (!empty($filtros['Segundo_Ape'])) {
            $sql .= " AND Segundo_Ape LIKE :segundo_ap";
            $parametros[':segundo_ap'] = '%' . $filtros['Segundo_Ape'] . '%';
        }

        if (!empty($filtros['Codigo_Postal'])) {
            $sql .= " AND Codigo_Postal LIKE :codigo_postal";
            $parametros[':codigo_postal'] = '%' . $filtros['Codigo_Postal'] . '%';
        }

        if (!empty($filtros['id_Medico_Cabecera'])) {
            $sql .= " AND id_Medico_Cabecera = :medico";
            $parametros[':medico'] = $filtros['id_Medico_Cabecera'];
        }

        $sql .= " ORDER BY id_Paciente ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($parametros);

        $filas = $consulta->fetchAll();
        $pacientes = [];

        foreach ($filas as $fila) {
            $pacientes[] = $this->crearObjetoPaciente($fila);
        }

        return $pacientes;
    }

    // Convertir registro de la BD en objeto Paciente
    private function crearObjetoPaciente($fila) {

        return new Paciente(
            $fila['SSN'],
            $fila['Nombre'],
            $fila['Primer_Ape'],
            $fila['Segundo_Ape'],
            $fila['Edad'],
            $fila['id_Medico_Cabecera'],
            $fila['Calle'],
            $fila['Numero'],
            $fila['Colonia'],
            $fila['Codigo_Postal'],
            $fila['id_Paciente']
        );
    }
}
