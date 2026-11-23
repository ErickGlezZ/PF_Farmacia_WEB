
<?php

session_start();

// Cargar el autoload
require_once __DIR__ . '/../../../backend/config/autoload.php';

use App\facade\FarmaciaFacade;

try {

    // Crear la fachada
    $farmacia = new FarmaciaFacade();

    // Obtener las recetas
    $recetas = $farmacia->listarRecetas();

    // Obtener médicos y pacientes
    $medicos = $farmacia->listarMedicos();
    $pacientes = $farmacia->listarPacientes();

    // Crear arreglos para relacionar IDs con nombres
    $medicosPorId = [];

    foreach ($medicos as $medico) {

        $id = $medico->getIdMedico();

        $nombreCompleto = $medico->getNombre() . ' ' .
                          $medico->getPrimerAp() . ' ' .
                          $medico->getSegundoApe();

        $medicosPorId[$id] = trim($nombreCompleto);
    }

    $pacientesPorId = [];

    foreach ($pacientes as $paciente) {

        $id = $paciente->getIdPaciente();

        $nombreCompleto = $paciente->getNombre() . ' ' .
                          $paciente->getPrimerApe() . ' ' .
                          $paciente->getSegundoApe();

        $pacientesPorId[$id] = trim($nombreCompleto);
    }

    $errorListado = null;

} catch (Throwable $e) {

    $recetas = [];
    $medicosPorId = [];
    $pacientesPorId = [];
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

    <title>Recetas | Farmacias R-X</title>

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
                    No fue posible consultar las recetas:
                    <?= htmlspecialchars($errorListado, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php endif; ?>


            <!-- ENCABEZADO -->
            <header class="encabezado">

                <div>
                    <h1>Recetas</h1>
                    <p>Administración y gestión de recetas médicas.</p>
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
                        <h2>Listado de recetas</h2>
                        <p>Consulta y administra las recetas registradas.</p>
                    </div>

                    <a href="registrar.php" class="btn btn-agregar">
                        <i class="bi bi-plus-lg"></i>
                        Agregar receta
                    </a>

                </div>


                <!-- BUSCADOR -->
                <div class="medicos-herramientas">

                    <div class="buscador-medicos">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="buscarReceta"
                            class="form-control"
                            placeholder="Buscar por medicamento, médico o paciente..."
                        >

                    </div>

                    <span class="total-registros">
                        <i class="bi bi-file-medical"></i>
                        Recetas registradas: <?= count($recetas) ?>
                    </span>

                </div>


                <!-- TABLA -->
                <div class="tabla-contenedor">

                    <div class="table-responsive">

                        <table class="table tabla-medicos align-middle">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Medicamento</th>
                                    <th>Paciente</th>
                                    <th>Médico</th>
                                    <th>Fecha</th>
                                    <th>Cantidad</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaRecetas">

                                <?php if (count($recetas) > 0): ?>

                                    <?php foreach ($recetas as $receta): ?>

                                        <?php

                                            // Obtener los datos de la receta
                                            $id = $receta->getIdReceta();
                                            $idMedico = $receta->getIdMedico();
                                            $idPaciente = $receta->getIdPaciente();
                                            $medicamento = $receta->getMedicamento();
                                            $fecha = $receta->getFecha();
                                            $cantidad = $receta->getCantidad();
                                            $unidad = $receta->getUnidad();
                                            $indicaciones = $receta->getIndicaciones();

                                            // Obtener nombres del médico y paciente
                                            $medicoTexto = $medicosPorId[$idMedico] ?? 'No disponible';

                                            $pacienteTexto = $pacientesPorId[$idPaciente] ?? 'No disponible';

                                            // Iniciales para el avatar
                                            $iniciales = strtoupper(
                                                substr($medicamento, 0, 1)
                                            );

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
                                                            <?= htmlspecialchars($medicamento, ENT_QUOTES, 'UTF-8') ?>
                                                        </strong>

                                                        <small>
                                                            <?= htmlspecialchars($indicaciones, ENT_QUOTES, 'UTF-8') ?>
                                                        </small>
                                                    </div>

                                                </div>

                                            </td>

                                            <td>
                                                <?= htmlspecialchars($pacienteTexto, ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($medicoTexto, ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars((string)$cantidad, ENT_QUOTES, 'UTF-8') ?>
                                                <?= htmlspecialchars($unidad, ENT_QUOTES, 'UTF-8') ?>
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
                                                        data-medicamento="<?= htmlspecialchars($medicamento, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-medico="<?= htmlspecialchars($medicoTexto, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-paciente="<?= htmlspecialchars($pacienteTexto, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-fecha="<?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-cantidad="<?= htmlspecialchars((string)$cantidad, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-unidad="<?= htmlspecialchars($unidad, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-indicaciones="<?= htmlspecialchars($indicaciones, ENT_QUOTES, 'UTF-8') ?>"
                                                    >
                                                        <i class="bi bi-eye"></i>
                                                        Detalles
                                                    </button>


                                                    <!-- BOTÓN EDITAR -->
                                                    <a
                                                        href="editar.php?id=<?= urlencode((string)$id) ?>"
                                                        class="btn btn-editar"
                                                        title="Editar receta"
                                                    >
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>


                                                    <!-- FORMULARIO ELIMINAR -->
                                                    <form
                                                        action="../../scripts/procesar_bajas_recetas.php"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('¿Estás seguro de eliminar esta receta?');"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="ID_Receta"
                                                            value="<?= htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-eliminar"
                                                            title="Eliminar receta"
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
                                        <td colspan="7" class="text-center py-4">
                                            No hay recetas registradas.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- PIE DE TABLA -->
                    <div class="tabla-pie">

                        <span>
                            Mostrando <?= count($recetas) ?> recetas
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
                        <h5 class="modal-title">Detalles de la receta</h5>
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
                            <i class="bi bi-capsule"></i>
                        </div>

                        <div>
                            <h5 id="detalleMedicamento">Medicamento</h5>
                            <span id="detalleFecha">Fecha</span>
                        </div>

                    </div>

                    <div class="detalle-datos">

                        <div class="detalle-item">
                            <span>ID de la receta</span>
                            <strong id="detalleId">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Medicamento</span>
                            <strong id="detalleMedicamentoCampo">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Paciente</span>
                            <strong id="detallePaciente">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Médico</span>
                            <strong id="detalleMedico">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Fecha</span>
                            <strong id="detalleFechaCampo">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Cantidad</span>
                            <strong id="detalleCantidad">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Unidad</span>
                            <strong id="detalleUnidad">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Indicaciones</span>
                            <strong id="detalleIndicaciones">-</strong>
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
        const buscarReceta = document.getElementById('buscarReceta');

        buscarReceta.addEventListener('input', function () {

            const texto = this.value.toLowerCase();

            const filas = document.querySelectorAll('#tablaRecetas tr');

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
            const medicamento = boton.dataset.medicamento;
            const medico = boton.dataset.medico;
            const paciente = boton.dataset.paciente;
            const fecha = boton.dataset.fecha;
            const cantidad = boton.dataset.cantidad;
            const unidad = boton.dataset.unidad;
            const indicaciones = boton.dataset.indicaciones;

            document.getElementById('detalleId').textContent = id;

            document.getElementById('detalleMedicamento').textContent =
                medicamento;

            document.getElementById('detalleFecha').textContent =
                fecha;

            document.getElementById('detalleMedicamentoCampo').textContent =
                medicamento;

            document.getElementById('detallePaciente').textContent =
                paciente;

            document.getElementById('detalleMedico').textContent =
                medico;

            document.getElementById('detalleFechaCampo').textContent =
                fecha;

            document.getElementById('detalleCantidad').textContent =
                cantidad;

            document.getElementById('detalleUnidad').textContent =
                unidad;

            document.getElementById('detalleIndicaciones').textContent =
                indicaciones;

        });

    </script>

</body>
</html>
