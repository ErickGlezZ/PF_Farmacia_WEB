<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../backend/config/Autoload.php';

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/medicos/registrar.php");
    exit;
}

try {

    // Obtener la conexión a la base de datos
    $conexionBD = \App\config\conexion::getInstance();
    $conexion = $conexionBD->getConnection();

    // Crear las dependencias
    $medicoDAO = new \App\DAO\MedicoDAO($conexion);

    $medicoService = new \App\Services\MedicoService($medicoDAO);

    $medicoController = new \App\Controllers\MedicoController($medicoService);

    // Enviar los datos al controlador
    $medicoController->registrar($_POST);

    // Limpiar datos anteriores
    unset($_SESSION['datos_medico']);

    // Mensaje de éxito
    $_SESSION['mensaje'] = "Médico registrado exitosamente.";
    $_SESSION['tipo_mensaje'] = "success";

    // Regresar al listado
    header("Location: ../pages/medicos/consultar.php");
    exit;

} catch (Throwable $e) {

    // Guardar los datos escritos en el formulario
    $_SESSION['datos_medico'] = $_POST;

    // Guardar el mensaje de error
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";

    // Regresar al formulario
    header("Location: ../pages/medicos/registrar.php");
    exit;

}
