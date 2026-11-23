<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../../backend/config/autoload.php';

use App\facade\FarmaciaFacade;


try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    
    $pacientes = $farmacia->listarPacientes();

    
    $medicos = $farmacia->listarMedicos();

    
    $medicosPorId = [];

foreach ($medicos as $medico) {

    $id = $medico->getIdMedico();

    $nombreCompleto = $medico->getNombre() . ' ' .
                      $medico->getPrimerAp() . ' ' .
                      $medico->getSegundoApe();

    $medicosPorId[$id] = trim($nombreCompleto);
}

$errorListado = null;

} catch (Throwable $e) {

    $pacientes = [];
    $errorListado = $e->getMessage();

}

// Tipo de mensaje permitido
$tipoMensaje = $_SESSION['tipo_mensaje'] ?? 'info';

if (!in_array($tipoMensaje, ['success', 'danger', 'warning', 'info'], true)) {
    $tipoMensaje = 'info';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pacientes | Farmacias R-X</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/listados.css">

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

            <!-- MENSAJES DE SESIÓN -->
            <?php if (isset($_SESSION['mensaje'])): ?>

                <div class="alert alert-<?= $tipoMensaje ?> alert-dismissible fade show"
                     role="alert">

                    <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8') ?>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert" aria-label="Cerrar"></button>

                </div>

                <?php
                    unset($_SESSION['mensaje']);
                    unset($_SESSION['tipo_mensaje']);
                ?>

            <?php endif; ?>


            <!-- ERROR DE CONSULTA -->
            <?php if ($errorListado !== null): ?>

                <div class="alert alert-danger" role="alert">
                    No fue posible consultar los pacientes:
                    <?= htmlspecialchars($errorListado, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php endif; ?>


            <!-- ENCABEZADO -->
            <header class="encabezado">

                <div>
                    <h1>Pacientes</h1>
                    <p>Administración y gestión de pacientes registrados.</p>
                </div>

                <div class="usuario-info">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </div>

            </header>


            <!-- CONTENIDO DEL LISTADO -->
            <section class="seccion-medicos">

                <div class="medicos-superior">

                    <div>
                        <h2>Listado de pacientes</h2>
                        <p>Consulta y administra la información de los pacientes.</p>
                    </div>

                    <a href="registrar.php" class="btn btn-agregar">
                        <i class="bi bi-plus-lg"></i>
                        Agregar paciente
                    </a>

                </div>


                <!-- BUSCADOR -->
                <div class="medicos-herramientas">

                    <div class="buscador-medicos">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="buscarPaciente"
                            class="form-control"
                            placeholder="Buscar por nombre, SSN o colonia..."
                        >

                    </div>

                    <span class="total-registros">
                        <i class="bi bi-people"></i>
                        Pacientes registrados: <?= count($pacientes) ?>
                    </span>

                </div>


                <!-- TABLA -->
                <div class="tabla-contenedor">

                    <div class="table-responsive">

                        <table class="table tabla-medicos align-middle">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Paciente</th>
                                    <th>SSN</th>
                                    <th>Edad</th>
                                    <th>Médico de cabecera</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaPacientes">

                                <?php if (count($pacientes) > 0): ?>

                                    <?php foreach ($pacientes as $paciente): ?>

                                        <?php
                                            // Obtener los datos del paciente
                                            $id = $paciente->getIdPaciente();
                                            $ssn = $paciente->getSSN();
                                            $nombre = $paciente->getNombre();
                                            $primerAp = $paciente->getPrimerApe();
                                            $segundoAp = $paciente->getSegundoApe();
                                            $edad = $paciente->getEdad();
                                            $idMedico = $paciente->getIdMedicoCabecera();
                                            $calle = $paciente->getCalle();
                                            $numero = $paciente->getNumero();
                                            $colonia = $paciente->getColonia();
                                            $codigoPostal = $paciente->getCodigoPostal();

                                            // Iniciales para el avatar
                                            $iniciales = strtoupper(
                                                substr($nombre, 0, 1) .
                                                substr($primerAp, 0, 1)
                                            );

                                            // Obtener el nombre del médico de cabecera
                                            $medicoTexto = ($idMedico !== null && isset($medicosPorId[$idMedico]))
                                                ? $medicosPorId[$idMedico]
                                                : 'Sin asignar';
                                        ?>

                                        <tr>

                                            <td>
                                                <?= htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>

                                                <div class="medico-nombre">

                                                    <div class="avatar-medico">
                                                        <?= htmlspecialchars($iniciales, ENT_QUOTES, 'UTF-8') ?>
                                                    </div>

                                                    <div>
                                                        <strong>
                                                            <?= htmlspecialchars($nombre . ' ' . $primerAp, ENT_QUOTES, 'UTF-8') ?>
                                                        </strong>

                                                        <small>
                                                            <?= htmlspecialchars($segundoAp, ENT_QUOTES, 'UTF-8') ?>
                                                        </small>
                                                    </div>

                                                </div>

                                            </td>

                                            <td>
                                                <?= htmlspecialchars($ssn, ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars((string)$edad, ENT_QUOTES, 'UTF-8') ?>
                                                años
                                            </td>

                                            <td>
                                                <?= htmlspecialchars((string)$medicoTexto, ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>

                                                <div class="acciones">

                                                    <!-- BOTÓN DETALLES -->
                                                    <button
                                                        type="button"
                                                        class="btn btn-detalles"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetalles"
                                                        data-id="<?= htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ssn="<?= htmlspecialchars($ssn, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-nombre="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-primer-ap="<?= htmlspecialchars($primerAp, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-segundo-ap="<?= htmlspecialchars($segundoAp, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-edad="<?= htmlspecialchars((string)$edad, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-medico="<?= htmlspecialchars((string)$medicoTexto, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-calle="<?= htmlspecialchars($calle, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-numero="<?= htmlspecialchars($numero, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-colonia="<?= htmlspecialchars($colonia, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-codigo-postal="<?= htmlspecialchars($codigoPostal, ENT_QUOTES, 'UTF-8') ?>"
                                                    >
                                                        <i class="bi bi-eye"></i>
                                                        Detalles
                                                    </button>


                                                    <!-- BOTÓN EDITAR -->
                                                    <a
                                                        href="editar.php?id=<?= urlencode((string)$id) ?>"
                                                        class="btn btn-editar"
                                                        title="Editar paciente"
                                                    >
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>


                                                    <!-- FORMULARIO ELIMINAR -->
                                                    <form
                                                        action="../../scripts/procesar_bajas_pacientes.php"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('¿Estás seguro de eliminar este paciente? También podrían eliminarse sus recetas asociadas. ¿Deseas continuar?');"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="id_Paciente"
                                                            value="<?= htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-eliminar"
                                                            title="Eliminar paciente"
                                                        >
                                                            <i class="bi bi-trash3"></i>
                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            No hay pacientes registrados.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- PIE DE TABLA -->
                    <div class="tabla-pie">

                        <span>
                            Mostrando <?= count($pacientes) ?> pacientes
                        </span>

                    </div>

                </div>

            </section>

        </main>

    </div>


    <!-- MODAL DE DETALLES -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title">Detalles del paciente</h5>
                        <small class="text-secondary">Información completa del registro</small>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="detalle-identidad">

                        <div class="avatar-detalle">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>
                            <h5 id="detalleNombre">Nombre del paciente</h5>
                            <span id="detalleEdad">Edad</span>
                        </div>

                    </div>

                    <div class="detalle-datos">

                        <div class="detalle-item">
                            <span>ID del paciente</span>
                            <strong id="detalleId">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>SSN</span>
                            <strong id="detalleSSN">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Nombre</span>
                            <strong id="detalleNombreCampo">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Primer apellido</span>
                            <strong id="detallePrimerAp">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Segundo apellido</span>
                            <strong id="detalleSegundoAp">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Edad</span>
                            <strong id="detalleEdadCampo">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Médico de cabecera</span>
                            <strong id="detalleMedico">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Calle</span>
                            <strong id="detalleCalle">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Número</span>
                            <strong id="detalleNumero">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Colonia</span>
                            <strong id="detalleColonia">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Código postal</span>
                            <strong id="detalleCodigoPostal">-</strong>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-cerrar"
                        data-bs-dismiss="modal"
                    >
                        Cerrar
                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


    <!-- FUNCIONALIDAD -->
    <script>

        // BUSCADOR
        const buscarPaciente = document.getElementById('buscarPaciente');

        buscarPaciente.addEventListener('input', function () {

            const texto = this.value.toLowerCase();

            const filas = document.querySelectorAll('#tablaPacientes tr');

            filas.forEach(function (fila) {

                const contenido = fila.textContent.toLowerCase();

                fila.style.display = contenido.includes(texto) ? '' : 'none';

            });

        });


        // CARGAR DATOS EN EL MODAL
        const modalDetalles = document.getElementById('modalDetalles');

        modalDetalles.addEventListener('show.bs.modal', function (evento) {

            const boton = evento.relatedTarget;

            const id = boton.dataset.id;
            const ssn = boton.dataset.ssn;
            const nombre = boton.dataset.nombre;
            const primerAp = boton.dataset.primerAp;
            const segundoAp = boton.dataset.segundoAp;
            const edad = boton.dataset.edad;
            const medico = boton.dataset.medico;
            const calle = boton.dataset.calle;
            const numero = boton.dataset.numero;
            const colonia = boton.dataset.colonia;
            const codigoPostal = boton.dataset.codigoPostal;

            document.getElementById('detalleId').textContent = id;
            document.getElementById('detalleSSN').textContent = ssn;

            document.getElementById('detalleNombre').textContent =
                nombre + ' ' + primerAp;

            document.getElementById('detalleEdad').textContent =
                edad + ' años';

            document.getElementById('detalleNombreCampo').textContent = nombre;
            document.getElementById('detallePrimerAp').textContent = primerAp;
            document.getElementById('detalleSegundoAp').textContent = segundoAp;
            document.getElementById('detalleEdadCampo').textContent = edad + ' años';
            document.getElementById('detalleMedico').textContent = medico;
            document.getElementById('detalleCalle').textContent = calle;
            document.getElementById('detalleNumero').textContent = numero;
            document.getElementById('detalleColonia').textContent = colonia;
            document.getElementById('detalleCodigoPostal').textContent = codigoPostal;

        });

    </script>

</body>
</html>
