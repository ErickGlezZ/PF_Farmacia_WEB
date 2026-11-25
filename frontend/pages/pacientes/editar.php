<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../../backend/config/Autoload.php';

use App\facade\FarmaciaFacade;
use App\Middleware\AuthMiddleware;

// Proteger el dashboard
AuthMiddleware::requireRole(
    ['Administrador', 'Empleado'],
    '../login.php'
);

// Validar el ID recibido
if (
    !isset($_GET['id']) ||
    !ctype_digit($_GET['id']) ||
    (int) $_GET['id'] <= 0
) {
    $_SESSION['mensaje'] = "El ID del paciente no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: consultar.php");
    exit;
}

$id = (int) $_GET['id'];

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Consultar paciente
    $paciente = $farmacia->consultarPaciente($id);

    // Verificar que exista
    if (!$paciente) {
        $_SESSION['mensaje'] = "No se encontró el paciente que intentas editar.";
        $_SESSION['tipo_mensaje'] = "warning";

        header("Location: consultar.php");
        exit;
    }

    // Consultar médicos para el selector
    $medicos = $farmacia->listarMedicos();

} catch (Throwable $e) {

    $_SESSION['mensaje'] = "Ocurrió un error al consultar al paciente.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: consultar.php");
    exit;
}

// Recuperar datos anteriores si hubo un error al actualizar
$datosPaciente = $_SESSION['datos_paciente_edicion'] ?? [];

// Limpiar datos de sesión
unset($_SESSION['datos_paciente_edicion']);

// Recuperar mensaje
$mensaje = $_SESSION['mensaje'] ?? null;
$tipoMensaje = $_SESSION['tipo_mensaje'] ?? 'info';

unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);

// Obtener datos del paciente
$idPaciente = $paciente->getIdPaciente();

$ssn = $datosPaciente['SSN'] ?? $paciente->getSSN();
$nombre = $datosPaciente['Nombre'] ?? $paciente->getNombre();
$primerAp = $datosPaciente['Primer_Ape'] ?? $paciente->getPrimerApe();
$segundoApe = $datosPaciente['Segundo_Ape'] ?? $paciente->getSegundoApe();
$edad = $datosPaciente['Edad'] ?? $paciente->getEdad();
$idMedico = $datosPaciente['id_Medico_Cabecera'] ?? $paciente->getIdMedicoCabecera();
$calle = $datosPaciente['Calle'] ?? $paciente->getCalle();
$numero = $datosPaciente['Numero'] ?? $paciente->getNumero();
$colonia = $datosPaciente['Colonia'] ?? $paciente->getColonia();
$codigoPostal = $datosPaciente['Codigo_Postal'] ?? $paciente->getCodigoPostal();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar paciente | Farmacias R-X</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/listados.css">
    <link rel="stylesheet" href="../../css/registrar.css">
</head>

<body>

    <div class="contenedor">

        <!-- MENÚ LATERAL -->
        <aside class="sidebar">

            <div class="sidebar-logo">
                <i class="bi bi-capsule-pill"></i>
                <span>Farmacias R-X</span>
            </div>

            <div class="sidebar-menu">

                <p class="menu-titulo">MENÚ PRINCIPAL</p>

                <a href="../dashboard.php" class="menu-enlace">
                    <i class="bi bi-house-door"></i>
                    <span>Inicio</span>
                </a>

                <a href="../medicos/consultar.php" class="menu-enlace">
                    <i class="bi bi-person-badge"></i>
                    <span>Médicos</span>
                </a>

                <a href="consultar.php" class="menu-enlace activo">
                    <i class="bi bi-people"></i>
                    <span>Pacientes</span>
                </a>

                <a href="../recetas/consultar.php" class="menu-enlace">
                    <i class="bi bi-file-medical"></i>
                    <span>Recetas</span>
                </a>

            </div>

            <div class="sidebar-footer">

                <a href="../login.php" class="menu-enlace">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Cerrar sesión</span>
                </a>

            </div>

        </aside>


        <!-- CONTENIDO PRINCIPAL -->
        <main class="contenido">

            <!-- MENSAJE DE ALERTA -->
            <?php if ($mensaje): ?>

                <div class="alert alert-<?= htmlspecialchars($tipoMensaje, ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show"
                     role="alert">

                    <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            <?php endif; ?>


            <!-- ENCABEZADO -->
            <header class="encabezado">

                <div>
                    <h1>Editar paciente</h1>
                    <p>Modifica la información del paciente seleccionado.</p>
                </div>

                <div class="usuario-info">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </div>

            </header>


            <!-- FORMULARIO -->
            <section class="seccion-registro">

                <div class="registro-superior">

                    <div>
                        <a href="consultar.php" class="volver-listado">
                            <i class="bi bi-arrow-left"></i>
                            Volver al listado
                        </a>

                        <h2>Información del paciente</h2>

                        <p>
                            Modifica los siguientes campos para actualizar la información.
                        </p>
                    </div>

                </div>


                <div class="formulario-contenedor">

                    <div class="formulario-encabezado">

                        <div class="icono-formulario">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <div>
                            <h3>Datos personales y domicilio</h3>
                            <p>Los campos marcados con * son obligatorios.</p>
                        </div>

                    </div>


                    <form action="../../scripts/procesar_cambios_pacientes.php" method="POST">

                        <!-- ID DEL PACIENTE -->
                        <input
                            type="hidden"
                            name="id_Paciente"
                            value="<?= htmlspecialchars((string)$idPaciente, ENT_QUOTES, 'UTF-8') ?>"
                        >


                        <!-- IDENTIFICACIÓN -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-card-text"></i>
                                Identificación
                            </h4>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label for="SSN" class="form-label">
                                        SSN *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="SSN"
                                        name="SSN"
                                        maxlength="11"
                                        minlength="11"
                                        pattern="[0-9]{11}"
                                        placeholder="Ej. 12345678901"
                                        required
                                        value="<?= htmlspecialchars((string)$ssn, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                    <small class="form-text">
                                        Ingresa los 11 dígitos del identificador.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <!-- DATOS PERSONALES -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-person"></i>
                                Datos personales
                            </h4>

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label for="Nombre" class="form-label">
                                        Nombre *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Nombre"
                                        name="Nombre"
                                        maxlength="50"
                                        required
                                        value="<?= htmlspecialchars((string)$nombre, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-4">

                                    <label for="Primer_Ap" class="form-label">
                                        Primer apellido *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Primer_Ape"
                                        name="Primer_Ape"
                                        maxlength="50"
                                        required
                                        value="<?= htmlspecialchars((string)$primerAp, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-4">

                                    <label for="Segundo_Ape" class="form-label">
                                        Segundo apellido *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Segundo_Ape"
                                        name="Segundo_Ape"
                                        maxlength="50"
                                        required
                                        value="<?= htmlspecialchars((string)$segundoApe, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="Edad" class="form-label">
                                        Edad *
                                    </label>

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="Edad"
                                        name="Edad"
                                        min="1"
                                        max="120"
                                        step="1"
                                        required
                                        value="<?= htmlspecialchars((string)$edad, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="id_Medico_Cabecera" class="form-label">
                                        Médico de cabecera
                                    </label>

                                    <select
                                        class="form-select"
                                        id="id_Medico_Cabecera"
                                        name="id_Medico_Cabecera"
                                    >

                                        <option value="">
                                            Sin médico asignado
                                        </option>

                                        <?php foreach ($medicos as $medicoItem): ?>

                                            <?php
                                            $idMedicoItem = $medicoItem->getIdMedico();

                                            $nombreMedico = $medicoItem->getNombre() . ' ' .
                                                            $medicoItem->getPrimerAp() . ' ' .
                                                            $medicoItem->getSegundoApe();
                                            ?>

                                            <option
                                                value="<?= htmlspecialchars((string)$idMedicoItem, ENT_QUOTES, 'UTF-8') ?>"
                                                <?= ((string)$idMedico === (string)$idMedicoItem) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars(trim($nombreMedico), ENT_QUOTES, 'UTF-8') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <small class="form-text">
                                        Puedes seleccionar un médico o dejarlo sin asignar.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <!-- DOMICILIO -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-geo-alt"></i>
                                Domicilio
                            </h4>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label for="Calle" class="form-label">
                                        Calle *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Calle"
                                        name="Calle"
                                        maxlength="100"
                                        required
                                        value="<?= htmlspecialchars((string)$calle, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="Numero" class="form-label">
                                        Número *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Numero"
                                        name="Numero"
                                        maxlength="10"
                                        required
                                        value="<?= htmlspecialchars((string)$numero, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="Colonia" class="form-label">
                                        Colonia *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Colonia"
                                        name="Colonia"
                                        maxlength="50"
                                        required
                                        value="<?= htmlspecialchars((string)$colonia, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="Codigo_Postal" class="form-label">
                                        Código postal *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Codigo_Postal"
                                        name="Codigo_Postal"
                                        maxlength="5"
                                        minlength="5"
                                        pattern="[0-9]{5}"
                                        placeholder="Ej. 99300"
                                        required
                                        value="<?= htmlspecialchars((string)$codigoPostal, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>

                            </div>

                        </div>


                        <!-- BOTONES -->
                        <div class="formulario-acciones">

                            <a href="consultar.php" class="btn btn-cancelar">
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-guardar">
                                <i class="bi bi-check-lg"></i>
                                Guardar cambios
                            </button>

                        </div>

                    </form>

                </div>

            </section>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
