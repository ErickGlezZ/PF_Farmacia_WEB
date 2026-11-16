<?php

namespace App\models;

class Medico {

    private $nombre;
    private $especialidad;

    public function __construct($nombre, $especialidad) {
        $this->nombre = $nombre;
        $this->especialidad = $especialidad;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEspecialidad() {
        return $this->especialidad;
    }

}
