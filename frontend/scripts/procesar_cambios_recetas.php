<?php

session_start();

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;
use App\Middleware\AuthMiddleware;


AuthMiddleware::requireRole(
    ['Administrador', 'Empleado'],
    '../pages/login.php'
);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/recetas/consultar.php");
    exit;
}

$id = $_POST['ID_Receta'] ?? '';

if (!ctype_digit((string)$id) || (int)$id <= 0) {

    $_SESSION['mensaje'] = "El ID de la receta no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/recetas/consultar.php");
    exit;
}

$id = (int) $id;

try {

    $farmacia = new FarmaciaFacade();

    $farmacia->actualizarReceta($_POST);

    unset($_SESSION['datos_receta_edicion']);

    $_SESSION['mensaje'] = "Receta actualizada exitosamente.";
    $_SESSION['tipo_mensaje'] = "success";

    header("Location: ../pages/recetas/consultar.php");
    exit;

} catch (Throwable $e) {

    $_SESSION['datos_receta_edicion'] = $_POST;


    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    // Regresar al formulario de edición conservando el ID
    header("Location: ../pages/recetas/editar.php?id=" . urlencode((string)$id));
    exit;

}
