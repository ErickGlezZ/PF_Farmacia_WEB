<?php

namespace App\Interfaces;

interface PacienteRepositoryInterface {

    public function guardar($paciente);

    public function buscarPorId($id);

    public function listar();

    public function actualizar($paciente);

    public function eliminar($id);

    public function buscarPorFiltros($filtros);
}
