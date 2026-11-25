<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;
use App\Middleware\AuthMiddleware;

AuthMiddleware::requireRole(
    ['Administrador'],
    '../pages/login.php'
);
// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/pacientes/consultar.php");
    exit;
}

// Verificar que se haya recibido el ID
if (!isset($_POST['id_Paciente']) || !ctype_digit($_POST['id_Paciente']) || (int)$_POST['id_Paciente'] <= 0) {

    $_SESSION['mensaje'] = "El ID del paciente no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/pacientes/consultar.php");
    exit;
}

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Enviar el ID al controlador
    $resultado = $farmacia->eliminarPaciente((int)$_POST['id_Paciente']);

    if ($resultado) {

        $_SESSION['mensaje'] = "Paciente eliminado exitosamente.";
        $_SESSION['tipo_mensaje'] = "success";

    } else {

        $_SESSION['mensaje'] = "No se encontró el paciente que intentas eliminar.";
        $_SESSION['tipo_mensaje'] = "warning";

    }

    header("Location: ../pages/pacientes/consultar.php");
    exit;

} catch (Throwable $e) {

    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/pacientes/consultar.php");
    exit;
}
