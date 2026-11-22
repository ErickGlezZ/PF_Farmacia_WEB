<?php

namespace App\Models;

class Receta {

    private $ID_Receta;
    private $id_Medico;
    private $id_Paciente;
    private $Medicamento;
    private $Fecha;
    private $Cantidad;
    private $Unidad;
    private $Indicaciones;

    public function __construct(
        $id_Medico,
        $id_Paciente,
        $Medicamento,
        $Fecha,
        $Cantidad,
        $Unidad,
        $Indicaciones,
        $ID_Receta = null
    ) {
        $this->id_Medico = $id_Medico;
        $this->id_Paciente = $id_Paciente;
        $this->Medicamento = $Medicamento;
        $this->Fecha = $Fecha;
        $this->Cantidad = $Cantidad;
        $this->Unidad = $Unidad;
        $this->Indicaciones = $Indicaciones;
        $this->ID_Receta = $ID_Receta;
    }

    // Getters

    public function getIdReceta() {
        return $this->ID_Receta;
    }

    public function getIdMedico() {
        return $this->id_Medico;
    }

    public function getIdPaciente() {
        return $this->id_Paciente;
    }

    public function getMedicamento() {
        return $this->Medicamento;
    }

    public function getFecha() {
        return $this->Fecha;
    }

    public function getCantidad() {
        return $this->Cantidad;
    }

    public function getUnidad() {
        return $this->Unidad;
    }

    public function getIndicaciones() {
        return $this->Indicaciones;
    }

    // Setter

    public function setIdReceta($ID_Receta) {
        $this->ID_Receta = $ID_Receta;
    }
}
