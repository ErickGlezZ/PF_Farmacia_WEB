<?php

session_start();

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/recetas/registrar.php");
    exit;
}

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Registrar receta
    $farmacia->registrarReceta($_POST);

    unset($_SESSION['datos_receta']);

    $_SESSION['mensaje'] = "Receta registrada exitosamente.";
    $_SESSION['tipo_mensaje'] = "success";

    header("Location: ../pages/recetas/consultar.php");
    exit;

} catch (\Throwable $e) {

    $_SESSION['datos_receta'] = $_POST;
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/recetas/registrar.php");
    exit;
}
