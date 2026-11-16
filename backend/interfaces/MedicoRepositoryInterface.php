<?php

namespace App\Interfaces;

interface MedicoRepositoryInterface {

    public function guardar($medico);

    public function buscarPorId($id);

    public function listar();

    public function actualizar($medico);

    public function eliminar($id);

    public function buscarPorFiltros($filtros);

}
