<?php

session_start();

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/pacientes/registrar.php");
    exit;
}

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Registrar paciente
    $farmacia->registrarPaciente($_POST);

    unset($_SESSION['datos_paciente']);

    $_SESSION['mensaje'] = "Paciente registrado exitosamente.";
    $_SESSION['tipo_mensaje'] = "success";

    header("Location: ../pages/pacientes/consultar.php");
    exit;

} catch (\Throwable $e) {

    $_SESSION['datos_paciente'] = $_POST;
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/pacientes/registrar.php");
    exit;
}
