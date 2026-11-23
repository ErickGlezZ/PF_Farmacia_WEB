<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../../backend/config/autoload.php';

use App\facade\FarmaciaFacade;

// Validar el ID recibido
if (
    !isset($_GET['id']) ||
    !ctype_digit($_GET['id']) ||
    (int) $_GET['id'] <= 0
) {
    $_SESSION['mensaje'] = "El ID del médico no es válido.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: consultar.php");
    exit;
}

$id = (int) $_GET['id'];

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Consultar médico
    $medico = $farmacia->consultarMedico($id);

    // Verificar que exista
    if (!$medico) {
        $_SESSION['mensaje'] = "No se encontró el médico que intentas editar.";
        $_SESSION['tipo_mensaje'] = "warning";

        header("Location: consultar.php");
        exit;
    }

} catch (Throwable $e) {

    $_SESSION['mensaje'] = "Ocurrió un error al consultar al médico.";
    $_SESSION['tipo_mensaje'] = "danger";

    header("Location: consultar.php");
    exit;
}

// Recuperar datos anteriores si hubo un error al actualizar
$datosMedico = $_SESSION['datos_medico_edicion'] ?? [];

// Limpiar datos de sesión
unset($_SESSION['datos_medico_edicion']);

// Recuperar mensaje
$mensaje = $_SESSION['mensaje'] ?? null;
$tipoMensaje = $_SESSION['tipo_mensaje'] ?? 'info';

unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);

// Obtener datos del médico
$idMedico = $medico->getIdMedico();

$ssn = $datosMedico['SSN'] ?? $medico->getSSN();
$nombre = $datosMedico['Nombre'] ?? $medico->getNombre();
$primerAp = $datosMedico['Primer_Ap'] ?? $medico->getPrimerAp();
$segundoApe = $datosMedico['Segundo_Ape'] ?? $medico->getSegundoApe();
$especialidad = $datosMedico['Especialidad'] ?? $medico->getEspecialidad();
$experiencia = $datosMedico['Años_Experiencia'] ?? $medico->getAñosExperiencia();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar médico | Farmacias R-X</title>

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

                <a href="consultar.php" class="menu-enlace activo">
                    <i class="bi bi-person-badge"></i>
                    <span>Médicos</span>
                </a>

                <a href="../pacientes/consultar.php" class="menu-enlace">
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
                    <h1>Editar médico</h1>
                    <p>Modifica la información del médico seleccionado.</p>
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

                        <h2>Información del médico</h2>

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
                            <h3>Datos personales y profesionales</h3>
                            <p>Los campos marcados con * son obligatorios.</p>
                        </div>

                    </div>


                    <form action="../../scripts/procesar_cambios_medicos.php" method="POST">

                        <!-- ID DEL MÉDICO -->
                        <input
                            type="hidden"
                            name="id_Medico"
                            value="<?= htmlspecialchars((string)$idMedico, ENT_QUOTES, 'UTF-8') ?>"
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
                                        id="Primer_Ap"
                                        name="Primer_Ap"
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

                            </div>

                        </div>


                        <!-- INFORMACIÓN PROFESIONAL -->
                        <div class="grupo-formulario">

                            <h4>
                                <i class="bi bi-briefcase"></i>
                                Información profesional
                            </h4>

                            <div class="row g-3">

                                
                                <div class="col-md-6">

                                    <label for="Especialidad" class="form-label">
                                        Especialidad *
                                    </label>

                                    <?php
                                    $especialidadActual = $datosMedico['Especialidad']
                                        ?? $medico->getEspecialidad();
                                    ?>

                                    <select
                                        class="form-select"
                                        id="Especialidad"
                                        name="Especialidad"
                                        required
                                    >
                                        <option value="" disabled>Selecciona una especialidad</option>

                                        <?php
                                        $especialidades = [
                                            'Medicina General',
                                            'Cardiología',
                                            'Dermatología',
                                            'Pediatría',
                                            'Ginecología y Obstetricia',
                                            'Neurología',
                                            'Oftalmología',
                                            'Traumatología y Ortopedia',
                                            'Psiquiatría',
                                            'Urología',
                                            'Gastroenterología',
                                            'Endocrinología',
                                            'Oncología',
                                            'Neumología',
                                            'Nefrología',
                                            'Otorrinolaringología',
                                            'Cirugía General',
                                            'Medicina Interna',
                                            'Anestesiología',
                                            'Radiología'
                                        ];

                                        foreach ($especialidades as $especialidad):
                                        ?>
                                            <option
                                                value="<?= htmlspecialchars($especialidad, ENT_QUOTES, 'UTF-8') ?>"
                                                <?= ($especialidadActual === $especialidad) ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars($especialidad, ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>

                                </div>



                                <div class="col-md-6">

                                    <label for="Años_Experiencia" class="form-label">
                                        Años de experiencia *
                                    </label>

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="Años_Experiencia"
                                        name="Años_Experiencia"
                                        min="1"
                                        max="80"
                                        step="1"
                                        required
                                        value="<?= htmlspecialchars((string)$experiencia, ENT_QUOTES, 'UTF-8') ?>"
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
