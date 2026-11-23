<?php

namespace App\DAO;

use PDO;
use App\Models\Receta;
use App\Interfaces\RecetaRepositoryInterface;

class RecetaDAO implements RecetaRepositoryInterface {

    private $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // Registrar receta
    public function guardar($receta) {

        $sql = "INSERT INTO recetas
                (id_Medico, id_Paciente, Medicamento, Fecha,
                Cantidad, Unidad, Indicaciones)
                VALUES
                (:medico, :paciente, :medicamento, :fecha,
                :cantidad, :unidad, :indicaciones)";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':medico' => $receta->getIdMedico(),
            ':paciente' => $receta->getIdPaciente(),
            ':medicamento' => $receta->getMedicamento(),
            ':fecha' => $receta->getFecha(),
            ':cantidad' => $receta->getCantidad(),
            ':unidad' => $receta->getUnidad(),
            ':indicaciones' => $receta->getIndicaciones()
        ]);

        $id = $this->conexion->lastInsertId();
        $receta->setIdReceta($id);

        return $receta;
    }

    // Buscar receta por ID
    public function buscarPorId($id) {

        $sql = "SELECT * FROM recetas
                WHERE ID_Receta = :id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':id' => $id]);

        $fila = $consulta->fetch();

        if (!$fila) {
            return null;
        }

        return $this->crearObjetoReceta($fila);
    }

    // Listar recetas
    public function listar() {

        $sql = "SELECT * FROM recetas
                ORDER BY ID_Receta ASC";

        $consulta = $this->conexion->query($sql);
        $filas = $consulta->fetchAll();

        $recetas = [];

        foreach ($filas as $fila) {
            $recetas[] = $this->crearObjetoReceta($fila);
        }

        return $recetas;
    }

    // Actualizar receta
    public function actualizar($receta) {

        $sql = "UPDATE recetas SET
                id_Medico = :medico,
                id_Paciente = :paciente,
                Medicamento = :medicamento,
                Fecha = :fecha,
                Cantidad = :cantidad,
                Unidad = :unidad,
                Indicaciones = :indicaciones
                WHERE ID_Receta = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':medico' => $receta->getIdMedico(),
            ':paciente' => $receta->getIdPaciente(),
            ':medicamento' => $receta->getMedicamento(),
            ':fecha' => $receta->getFecha(),
            ':cantidad' => $receta->getCantidad(),
            ':unidad' => $receta->getUnidad(),
            ':indicaciones' => $receta->getIndicaciones(),
            ':id' => $receta->getIdReceta()
        ]);
    }

    // Eliminar receta
    public function eliminar($id) {

        $sql = "DELETE FROM recetas
                WHERE ID_Receta = :id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([':id' => $id]);
    }

    // Buscar recetas por filtros
    public function buscarPorFiltros($filtros) {

        $sql = "SELECT * FROM recetas WHERE 1 = 1";
        $parametros = [];

        if (!empty($filtros['ID_Receta'])) {
            $sql .= " AND ID_Receta = :id";
            $parametros[':id'] = $filtros['ID_Receta'];
        }

        if (!empty($filtros['id_Medico'])) {
            $sql .= " AND id_Medico = :medico";
            $parametros[':medico'] = $filtros['id_Medico'];
        }

        if (!empty($filtros['id_Paciente'])) {
            $sql .= " AND id_Paciente = :paciente";
            $parametros[':paciente'] = $filtros['id_Paciente'];
        }

        if (!empty($filtros['Medicamento'])) {
            $sql .= " AND Medicamento LIKE :medicamento";
            $parametros[':medicamento'] = '%' . $filtros['Medicamento'] . '%';
        }

        if (!empty($filtros['Fecha'])) {
            $sql .= " AND Fecha = :fecha";
            $parametros[':fecha'] = $filtros['Fecha'];
        }

        $sql .= " ORDER BY ID_Receta ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($parametros);

        $filas = $consulta->fetchAll();
        $recetas = [];

        foreach ($filas as $fila) {
            $recetas[] = $this->crearObjetoReceta($fila);
        }

        return $recetas;
    }

    // Convertir registro de la BD en objeto Receta
    private function crearObjetoReceta($fila) {

        return new Receta(
            $fila['id_Medico'],
            $fila['id_Paciente'],
            $fila['Medicamento'],
            $fila['Fecha'],
            $fila['Cantidad'],
            $fila['Unidad'],
            $fila['Indicaciones'],
            $fila['ID_Receta']
        );
    }
}
