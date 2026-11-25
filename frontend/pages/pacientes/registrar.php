<?php

session_start();

require_once dirname(__DIR__, 3) . '/backend/config/Autoload.php';

use App\facade\FarmaciaFacade;
use App\Middleware\AuthMiddleware;

// Proteger el dashboard
AuthMiddleware::requireRole(
    ['Administrador', 'Empleado'],
    '../login.php'
);

// Recuperar datos anteriores, si existen
$datosPaciente = $_SESSION['datos_paciente'] ?? [];

// Limpiar datos guardados en sesión
unset($_SESSION['datos_paciente']);

// Obtener médicos registrados
$farmacia = new FarmaciaFacade();
$medicos = $farmacia->listarMedicos();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar paciente | Farmacias R-X</title>

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
                    <h1>Registrar paciente</h1>
                    <p>Ingresa la información del nuevo paciente.</p>
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
                            Completa los siguientes campos para registrar un paciente.
                        </p>
                    </div>

                </div>


                <div class="formulario-contenedor">

                    <div class="formulario-encabezado">

                        <div class="icono-formulario">
                            <i class="bi bi-person-plus"></i>
                        </div>

                        <div>
                            <h3>Datos personales y domicilio</h3>
                            <p>Los campos marcados con * son obligatorios.</p>
                        </div>

                    </div>


                    <form action="../../scripts/procesar_altas_pacientes.php" method="POST">

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
                                        value="<?= htmlspecialchars($datosPaciente['SSN'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        placeholder="Nombre del paciente"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>


                                <div class="col-md-4">

                                    <label for="Primer_Ape" class="form-label">
                                        Primer apellido *
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="Primer_Ape"
                                        name="Primer_Ape"
                                        maxlength="50"
                                        placeholder="Primer apellido"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Primer_Ape'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        placeholder="Segundo apellido"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Segundo_Ape'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        placeholder="Ej. 25"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Edad'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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

                                        <?php foreach ($medicos as $medico): ?>

                                            <option
                                                value="<?= htmlspecialchars($medico->getIdMedico(), ENT_QUOTES, 'UTF-8') ?>"
                                                <?= (($datosPaciente['id_Medico_Cabecera'] ?? '') == $medico->getIdMedico()) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars(
                                                    $medico->getNombre() . ' ' .
                                                    $medico->getPrimerAp() . ' ' .
                                                    $medico->getSegundoApe(),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <small class="form-text">
                                        Puedes dejar este campo sin seleccionar.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <!-- DOMICILIO -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-house"></i>
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
                                        placeholder="Nombre de la calle"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Calle'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        placeholder="Número exterior"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Numero'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        placeholder="Nombre de la colonia"
                                        required
                                        value="<?= htmlspecialchars($datosPaciente['Colonia'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                                        value="<?= htmlspecialchars($datosPaciente['Codigo_Postal'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                    <small class="form-text">
                                        Ingresa los 5 dígitos del código postal.
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
                                Guardar paciente
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
