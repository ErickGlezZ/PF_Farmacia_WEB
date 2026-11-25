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
    header("Location: ../pages/recetas/consultar.php");
    exit;
}

// Verificar que se haya recibido el ID
if (!isset($_POST['ID_Receta']) || !ctype_digit($_POST['ID_Receta']) || (int)$_POST['ID_Receta'] <= 0) {

    $_SESSION['mensaje'] = "El ID de la receta no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/recetas/consultar.php");
    exit;
}

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Enviar el ID al controlador
    $resultado = $farmacia->eliminarReceta((int)$_POST['ID_Receta']);

    if ($resultado) {

        $_SESSION['mensaje'] = "Receta eliminada exitosamente.";
        $_SESSION['tipo_mensaje'] = "success";

    } else {

        $_SESSION['mensaje'] = "No se encontró la receta que intentas eliminar.";
        $_SESSION['tipo_mensaje'] = "warning";

    }

    header("Location: ../pages/recetas/consultar.php");
    exit;

} catch (Throwable $e) {

    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/recetas/consultar.php");
    exit;
}
