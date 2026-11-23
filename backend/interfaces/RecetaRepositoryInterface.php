<?php

namespace App\Interfaces;

interface RecetaRepositoryInterface {

    public function guardar($receta);

    public function buscarPorId($id);

    public function listar();

    public function actualizar($receta);

    public function eliminar($id);

    public function buscarPorFiltros($filtros);

}
