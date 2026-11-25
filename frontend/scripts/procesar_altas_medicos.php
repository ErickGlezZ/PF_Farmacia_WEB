<?php

session_start();

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;
use App\Middleware\AuthMiddleware;

AuthMiddleware::requireRole(
    ['Administrador', 'Empleado'],
    '../pages/login.php'
);

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/medicos/registrar.php");
    exit;
}

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Registrar médico
    $farmacia->registrarMedico($_POST);

    unset($_SESSION['datos_medico']);

    $_SESSION['mensaje'] = "Médico registrado exitosamente.";
    $_SESSION['tipo_mensaje'] = "success";

    header("Location: ../pages/medicos/consultar.php");
    exit;

} catch (\Throwable $e) {

    $_SESSION['datos_medico'] = $_POST;
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/medicos/registrar.php");
    exit;
}
