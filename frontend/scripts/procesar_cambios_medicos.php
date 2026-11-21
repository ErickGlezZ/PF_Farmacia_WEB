<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/medicos/consultar.php");
    exit;
}

// Obtener y validar el ID
$id = $_POST['id_Medico'] ?? '';

if (!ctype_digit((string)$id) || (int)$id <= 0) {

    $_SESSION['mensaje'] = "El ID del médico no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: ../pages/medicos/consultar.php");
    exit;
}

$id = (int) $id;

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Enviar los datos al controlador para actualizar
    $farmacia->actualizarMedico($_POST);

    // Limpiar datos anteriores
    unset($_SESSION['datos_medico_edicion']);

    // Mensaje de éxito
    $_SESSION['mensaje'] = "Médico actualizado exitosamente.";
    $_SESSION['tipo_mensaje'] = "success";

    // Regresar al listado
    header("Location: ../pages/medicos/consultar.php");
    exit;

} catch (Throwable $e) {

    // Conservar los datos capturados
    $_SESSION['datos_medico_edicion'] = $_POST;

    // Guardar el mensaje de error
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    // Regresar al formulario de edición conservando el ID
    header("Location: ../pages/medicos/editar.php?id=" . urlencode((string)$id));
    exit;

}
