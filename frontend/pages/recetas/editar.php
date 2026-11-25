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
    $_SESSION['mensaje'] = "El ID de la receta no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: consultar.php");
    exit;
}

$id = (int) $_GET['id'];

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Consultar receta
    $receta = $farmacia->consultarReceta($id);

    // Verificar que exista
    if (!$receta) {
        $_SESSION['mensaje'] = "No se encontró la receta que intentas editar.";
        $_SESSION['tipo_mensaje'] = "warning";

        header("Location: consultar.php");
        exit;
    }

    // Consultar médicos y pacientes para los selectores
    $medicos = $farmacia->listarMedicos();
    $pacientes = $farmacia->listarPacientes();

} catch (Throwable $e) {

    $_SESSION['mensaje'] = "Ocurrió un error al consultar la receta.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: consultar.php");
    exit;
}

// Recuperar datos anteriores si hubo un error al actualizar
$datosReceta = $_SESSION['datos_receta_edicion'] ?? [];

// Limpiar datos de sesión
unset($_SESSION['datos_receta_edicion']);

// Recuperar mensaje
$mensaje = $_SESSION['mensaje'] ?? null;
$tipoMensaje = $_SESSION['tipo_mensaje'] ?? 'info';

unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);

// Obtener datos de la receta
$idReceta = $receta->getIdReceta();

$idMedico = $datosReceta['id_Medico'] ?? $receta->getIdMedico();
$idPaciente = $datosReceta['id_Paciente'] ?? $receta->getIdPaciente();
$medicamento = $datosReceta['Medicamento'] ?? $receta->getMedicamento();
$fecha = $datosReceta['Fecha'] ?? $receta->getFecha();
$cantidad = $datosReceta['Cantidad'] ?? $receta->getCantidad();
$unidad = $datosReceta['Unidad'] ?? $receta->getUnidad();
$indicaciones = $datosReceta['Indicaciones'] ?? $receta->getIndicaciones();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar receta | Farmacias R-X</title>

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

                <a href="../pacientes/consultar.php" class="menu-enlace">
                    <i class="bi bi-people"></i>
                    <span>Pacientes</span>
                </a>

                <a href="consultar.php" class="menu-enlace activo">
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
                    <h1>Editar receta</h1>
                    <p>Modifica la información de la receta seleccionada.</p>
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

                        <h2>Información de la receta</h2>

                        <p>
                            Modifica los siguientes campos para actualizar la receta.
                        </p>
                    </div>

                </div>


                <div class="formulario-contenedor">

                    <div class="formulario-encabezado">

                        <div class="icono-formulario">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <div>
                            <h3>Datos de la receta médica</h3>
                            <p>Los campos marcados con * son obligatorios.</p>
                        </div>

                    </div>


                    <form action="../../scripts/procesar_cambios_recetas.php" method="POST">

                        <!-- ID DE LA RECETA -->
                        <input
                            type="hidden"
                            name="ID_Receta"
                            value="<?= htmlspecialchars((string)$idReceta, ENT_QUOTES, 'UTF-8') ?>"
                        >


                        <!-- PERSONAL MÉDICO Y PACIENTE -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-people"></i>
                                Médico y paciente
                            </h4>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label for="id_Medico" class="form-label">
                                        Médico *
                                    </label>

                                    <select
                                        class="form-select"
                                        id="id_Medico"
                                        name="id_Medico"
                                        required
                                    >

                                        <option value="">
                                            Selecciona un médico
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

                                </div>


                                <div class="col-md-6">

                                    <label for="id_Paciente" class="form-label">
                                        Paciente *
                                    </label>

                                    <select
                                        class="form-select"
                                        id="id_Paciente"
                                        name="id_Paciente"
                                        required
                                    >

                                        <option value="">
                                            Selecciona un paciente
                                        </option>

                                        <?php foreach ($pacientes as $pacienteItem): ?>

                                            <?php
                                            $idPacienteItem = $pacienteItem->getIdPaciente();

                                            $nombrePaciente = $pacienteItem->getNombre() . ' ' .
                                                              $pacienteItem->getPrimerApe() . ' ' .
                                                              $pacienteItem->getSegundoApe();
                                            ?>

                                            <option
                                                value="<?= htmlspecialchars((string)$idPacienteItem, ENT_QUOTES, 'UTF-8') ?>"
                                                <?= ((string)$idPaciente === (string)$idPacienteItem) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars(trim($nombrePaciente), ENT_QUOTES, 'UTF-8') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                        </div>


                        <!-- DATOS DEL MEDICAMENTO -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-capsule"></i>
                                Datos del medicamento
                            </h4>

                            <div class="row g-3">

                                <div class="col-md-12">

                                    <label for="Medicamento" class="form-label">
                                        Medicamento *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Medicamento"
                                        name="Medicamento"
                                        maxlength="100"
                                        placeholder="Nombre del medicamento"
                                        required
                                        value="<?= htmlspecialchars((string)$medicamento, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="Fecha" class="form-label">
                                        Fecha *
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control"
                                        id="Fecha"
                                        name="Fecha"
                                        required
                                        value="<?= htmlspecialchars((string)$fecha, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-3">

                                    <label for="Cantidad" class="form-label">
                                        Cantidad *
                                    </label>

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="Cantidad"
                                        name="Cantidad"
                                        min="1"
                                        step="1"
                                        required
                                        value="<?= htmlspecialchars((string)$cantidad, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-3">

                                    <label for="Unidad" class="form-label">
                                        Unidad *
                                    </label>

                                    <select
                                        class="form-select"
                                        id="Unidad"
                                        name="Unidad"
                                        required
                                    >

                                        <option value="">Selecciona</option>

                                        <?php
                                        $unidades = [
                                            'Tabletas',
                                            'Cápsulas',
                                            'Mililitros',
                                            'Gotas',
                                            'Piezas',
                                            'Sobres',
                                            'Aplicaciones'
                                        ];
                                        ?>

                                        <?php foreach ($unidades as $unidadItem): ?>

                                            <option
                                                value="<?= htmlspecialchars($unidadItem, ENT_QUOTES, 'UTF-8') ?>"
                                                <?= ($unidad === $unidadItem) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars($unidadItem, ENT_QUOTES, 'UTF-8') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <div class="col-md-12">

                                    <label for="Indicaciones" class="form-label">
                                        Indicaciones *
                                    </label>

                                    <textarea
                                        class="form-control"
                                        id="Indicaciones"
                                        name="Indicaciones"
                                        maxlength="100"
                                        rows="3"
                                        placeholder="Escribe las indicaciones del medicamento"
                                        required
                                    ><?= htmlspecialchars((string)$indicaciones, ENT_QUOTES, 'UTF-8') ?></textarea>

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