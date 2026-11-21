<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../backend/config/Autoload.php';

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/medicos/consultar.php");
    exit;
}

// Verificar que se haya recibido el ID
if (!isset($_POST['id_Medico']) || !ctype_digit($_POST['id_Medico']) || (int)$_POST['id_Medico'] <= 0) {

    $_SESSION['mensaje'] = "El ID del médico no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/medicos/consultar.php");
    exit;
}

try {

    // Obtener la conexión
    $conexionBD = \App\config\conexion::getInstance();
    $conexion = $conexionBD->getConnection();

    // Crear las dependencias
    $medicoDAO = new \App\DAO\MedicoDAO($conexion);

    $medicoService = new \App\Services\MedicoService($medicoDAO);

    $medicoController = new \App\Controllers\MedicoController($medicoService);

    // Enviar el ID al controlador
    $resultado = $medicoController->eliminar((int)$_POST['id_Medico']);

    if ($resultado) {

        $_SESSION['mensaje'] = "Médico eliminado exitosamente.";
        $_SESSION['tipo_mensaje'] = "success";

    } else {

        $_SESSION['mensaje'] = "No se encontró el médico que intentas eliminar.";
        $_SESSION['tipo_mensaje'] = "warning";

    }

    header("Location: ../pages/medicos/consultar.php");
    exit;

} catch (Throwable $e) {

    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/medicos/consultar.php");
    exit;
}
