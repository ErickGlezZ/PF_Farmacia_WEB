<?php

namespace App\Models;

class Medico {

    private $id_Medico;
    private $SSN;
    private $Nombre;
    private $Primer_Ap;
    private $Segundo_Ape;
    private $Especialidad;
    private $Años_Experiencia;

    public function __construct(
        $SSN,
        $Nombre,
        $Primer_Ap,
        $Segundo_Ape,
        $Especialidad,
        $Años_Experiencia,
        $id_Medico = null
    ) {

        $this->id_Medico = $id_Medico;
        $this->SSN = $SSN;
        $this->Nombre = $Nombre;
        $this->Primer_Ap = $Primer_Ap;
        $this->Segundo_Ape = $Segundo_Ape;
        $this->Especialidad = $Especialidad;
        $this->Años_Experiencia = $Años_Experiencia;

    }

    public function getIdMedico() {
        return $this->id_Medico;
    }

    public function getSSN() {
        return $this->SSN;
    }

    public function getNombre() {
        return $this->Nombre;
    }

    public function getPrimerAp() {
        return $this->Primer_Ap;
    }

    public function getSegundoApe() {
        return $this->Segundo_Ape;
    }

    public function getEspecialidad() {
        return $this->Especialidad;
    }

    public function getAñosExperiencia() {
        return $this->Años_Experiencia;
    }



    public function setIdMedico($id_Medico) {
        $this->id_Medico = $id_Medico;
    }
    public function setSSN($SSN) {
        $this->SSN = $SSN;
    }

    public function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    public function setPrimerAp($Primer_Ap) {
        $this->Primer_Ap = $Primer_Ap;
    }

    public function setSegundoApe($Segundo_Ape) {
        $this->Segundo_Ape = $Segundo_Ape;
    }

    public function setEspecialidad($Especialidad) {
        $this->Especialidad = $Especialidad;
    }

    public function setAñosExperiencia($Años_Experiencia) {
        $this->Años_Experiencia = $Años_Experiencia;
    }

}
