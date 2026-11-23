<?php

session_start();

require_once dirname(__DIR__, 3) . '/backend/config/Autoload.php';

use App\facade\FarmaciaFacade;

// Recuperar datos anteriores, si existen
$datosReceta = $_SESSION['datos_receta'] ?? [];

// Limpiar datos guardados en sesión
unset($_SESSION['datos_receta']);

// Obtener médicos y pacientes registrados
$farmacia = new FarmaciaFacade();

$medicos = $farmacia->listarMedicos();
$pacientes = $farmacia->listarPacientes();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar receta | Farmacias R-X</title>

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
            <?php if (isset($_SESSION['mensaje'])): ?>

                <div class="alert alert-<?= htmlspecialchars($_SESSION['tipo_mensaje'] ?? 'danger', ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show"
                     role="alert">

                    <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8') ?>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

                <?php
                    unset($_SESSION['mensaje']);
                    unset($_SESSION['tipo_mensaje']);
                ?>

            <?php endif; ?>


            <!-- ENCABEZADO -->
            <header class="encabezado">

                <div>
                    <h1>Registrar receta</h1>
                    <p>Ingresa la información de la nueva receta médica.</p>
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
                            Completa los siguientes campos para registrar una receta médica.
                        </p>
                    </div>

                </div>


                <div class="formulario-contenedor">

                    <div class="formulario-encabezado">

                        <div class="icono-formulario">
                            <i class="bi bi-file-medical"></i>
                        </div>

                        <div>
                            <h3>Datos de la receta médica</h3>
                            <p>Los campos marcados con * son obligatorios.</p>
                        </div>

                    </div>


                    <form action="../../scripts/procesar_altas_recetas.php" method="POST">


                        <!-- RELACIÓN MÉDICO Y PACIENTE -->
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

                                        <?php foreach ($medicos as $medico): ?>

                                            <option
                                                value="<?= htmlspecialchars((string)$medico->getIdMedico(), ENT_QUOTES, 'UTF-8') ?>"
                                                <?= (($datosReceta['id_Medico'] ?? '') == $medico->getIdMedico()) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars(
                                                    $medico->getNombre() . ' ' .
                                                    $medico->getPrimerAp() . ' ' .
                                                    $medico->getSegundoApe(),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                                - <?= htmlspecialchars($medico->getEspecialidad(), ENT_QUOTES, 'UTF-8') ?>
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

                                        <?php foreach ($pacientes as $paciente): ?>

                                            <option
                                                value="<?= htmlspecialchars((string)$paciente->getIdPaciente(), ENT_QUOTES, 'UTF-8') ?>"
                                                <?= (($datosReceta['id_Paciente'] ?? '') == $paciente->getIdPaciente()) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars(
                                                    $paciente->getNombre() . ' ' .
                                                    $paciente->getPrimerApe() . ' ' .
                                                    $paciente->getSegundoApe(),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
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

                                <div class="col-md-6">

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
                                        value="<?= htmlspecialchars($datosReceta['Medicamento'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        value="<?= htmlspecialchars($datosReceta['Fecha'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

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
                                        placeholder="Ej. 2"
                                        required
                                        value="<?= htmlspecialchars($datosReceta['Cantidad'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="Unidad" class="form-label">
                                        Unidad *
                                    </label>

                                    <select
                                        class="form-select"
                                        id="Unidad"
                                        name="Unidad"
                                        required
                                    >

                                        <option value="">
                                            Selecciona una unidad
                                        </option>

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

                                        <?php foreach ($unidades as $unidad): ?>

                                            <option
                                                value="<?= htmlspecialchars($unidad, ENT_QUOTES, 'UTF-8') ?>"
                                                <?= (($datosReceta['Unidad'] ?? '') === $unidad) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars($unidad, ENT_QUOTES, 'UTF-8') ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                        </div>


                        <!-- INDICACIONES -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-clipboard2-pulse"></i>
                                Indicaciones médicas
                            </h4>

                            <div class="row g-3">

                                <div class="col-12">

                                    <label for="Indicaciones" class="form-label">
                                        Indicaciones *
                                    </label>

                                    <textarea
                                        class="form-control"
                                        id="Indicaciones"
                                        name="Indicaciones"
                                        maxlength="100"
                                        rows="3"
                                        placeholder="Escribe las indicaciones del medicamento..."
                                        required
                                    ><?= htmlspecialchars($datosReceta['Indicaciones'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

                                    <small class="form-text">
                                        Máximo 100 caracteres.
                                    </small>

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
                                Guardar receta
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
