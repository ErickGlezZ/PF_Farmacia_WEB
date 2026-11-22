<?php
namespace App\Models;

class Paciente {

    private $id_Paciente;
    private $SSN;
    private $Nombre;
    private $Primer_Ape;
    private $Segundo_Ape;
    private $Edad;
    private $id_Medico_Cabecera;
    private $Calle;
    private $Numero;
    private $Colonia;
    private $Codigo_Postal;

    public function __construct(
        $SSN,
        $Nombre,
        $Primer_Ape,
        $Segundo_Ape,
        $Edad,
        $id_Medico_Cabecera,
        $Calle,
        $Numero,
        $Colonia,
        $Codigo_Postal,
        $id_Paciente = null
    ) {
        $this->SSN = $SSN;
        $this->Nombre = $Nombre;
        $this->Primer_Ape = $Primer_Ape;
        $this->Segundo_Ape = $Segundo_Ape;
        $this->Edad = $Edad;
        $this->id_Medico_Cabecera = $id_Medico_Cabecera;
        $this->Calle = $Calle;
        $this->Numero = $Numero;
        $this->Colonia = $Colonia;
        $this->Codigo_Postal = $Codigo_Postal;
        $this->id_Paciente = $id_Paciente;
    }

    // Getters

    public function getIdPaciente() {
        return $this->id_Paciente;
    }

    public function getSSN() {
        return $this->SSN;
    }

    public function getNombre() {
        return $this->Nombre;
    }

    public function getPrimerApe() {
        return $this->Primer_Ape;
    }

    public function getSegundoApe() {
        return $this->Segundo_Ape;
    }

    public function getEdad() {
        return $this->Edad;
    }

    public function getIdMedicoCabecera() {
        return $this->id_Medico_Cabecera;
    }

    public function getCalle() {
        return $this->Calle;
    }

    public function getNumero() {
        return $this->Numero;
    }

    public function getColonia() {
        return $this->Colonia;
    }

    public function getCodigoPostal() {
        return $this->Codigo_Postal;
    }

    // Setter

    public function setIdPaciente($id_Paciente) {
        $this->id_Paciente = $id_Paciente;
    }
}
