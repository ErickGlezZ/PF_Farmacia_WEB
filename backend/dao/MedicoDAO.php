<?php

namespace App\DAO;

use PDO;
use App\Models\Medico;
use App\Interfaces\MedicoRepositoryInterface;

class MedicoDAO implements MedicoRepositoryInterface {

    private $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    public function guardar($medico) {

        $sql = "INSERT INTO medicos
                (SSN, Nombre, Primer_Ap, Segundo_Ape, Especialidad, `Años_Experiencia`)
                VALUES
                (:ssn, :nombre, :primer_ap, :segundo_ape, :especialidad, :experiencia)";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':ssn' => $medico->getSSN(),
            ':nombre' => $medico->getNombre(),
            ':primer_ap' => $medico->getPrimerAp(),
            ':segundo_ape' => $medico->getSegundoApe(),
            ':especialidad' => $medico->getEspecialidad(),
            ':experiencia' => $medico->getAñosExperiencia()
        ]);


        $id = $this->conexion->lastInsertId();

        $medico->setIdMedico($id);

        return $medico;
    }

    public function buscarPorId($id) {

        $sql = "SELECT * FROM medicos WHERE id_Medico = :id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':id' => $id]);

        $fila = $consulta->fetch();

        if (!$fila) {
            return null;
        }

        return $this->crearObjetoMedico($fila);
    }

    public function listar() {

        $sql = "SELECT * FROM medicos ORDER BY id_Medico ASC";

        $consulta = $this->conexion->query($sql);

        $filas = $consulta->fetchAll();

        $medicos = [];

        foreach ($filas as $fila) {
            $medicos[] = $this->crearObjetoMedico($fila);
        }

        return $medicos;
    }


    public function actualizar($medico) {

        $sql = "UPDATE medicos SET
                SSN = :ssn,
                Nombre = :nombre,
                Primer_Ap = :primer_ap,
                Segundo_Ape = :segundo_ape,
                Especialidad = :especialidad,
                `Años_Experiencia` = :experiencia
                WHERE id_Medico = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':ssn' => $medico->getSSN(),
            ':nombre' => $medico->getNombre(),
            ':primer_ap' => $medico->getPrimerAp(),
            ':segundo_ape' => $medico->getSegundoApe(),
            ':especialidad' => $medico->getEspecialidad(),
            ':experiencia' => $medico->getAñosExperiencia(),
            ':id' => $medico->getIdMedico()
        ]);
    }

    // Eliminar médico
    public function eliminar($id) {

        $sql = "DELETE FROM medicos WHERE id_Medico = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([':id' => $id]);
    }

    // Buscar médicos por filtros
    public function buscarPorFiltros($filtros) {

        $sql = "SELECT * FROM medicos WHERE 1 = 1";
        $parametros = [];

        if (!empty($filtros['SSN'])) {
            $sql .= " AND SSN LIKE :ssn";
            $parametros[':ssn'] = '%' . $filtros['SSN'] . '%';
        }

        if (!empty($filtros['Nombre'])) {
            $sql .= " AND Nombre LIKE :nombre";
            $parametros[':nombre'] = '%' . $filtros['Nombre'] . '%';
        }

        if (!empty($filtros['Especialidad'])) {
            $sql .= " AND Especialidad LIKE :especialidad";
            $parametros[':especialidad'] = '%' . $filtros['Especialidad'] . '%';
        }

        $sql .= " ORDER BY id_Medico ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($parametros);

        $filas = $consulta->fetchAll();

        $medicos = [];

        foreach ($filas as $fila) {
            $medicos[] = $this->crearObjetoMedico($fila);
        }

        return $medicos;
    }

    private function crearObjetoMedico($fila) {

        return new Medico(
            $fila['SSN'],
            $fila['Nombre'],
            $fila['Primer_Ap'],
            $fila['Segundo_Ape'],
            $fila['Especialidad'],
            $fila['Años_Experiencia'],
            $fila['id_Medico']
        );
    }

}
