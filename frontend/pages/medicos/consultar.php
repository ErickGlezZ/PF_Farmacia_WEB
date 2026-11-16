
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Médicos | Farmacias R-X</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/medicos.css">
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

                <a href="listar.php" class="menu-enlace activo">
                    <i class="bi bi-person-badge"></i>
                    <span>Médicos</span>
                </a>

                <a href="../pacientes/listar.php" class="menu-enlace">
                    <i class="bi bi-people"></i>
                    <span>Pacientes</span>
                </a>

                <a href="../recetas/listar.php" class="menu-enlace">
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

            <!-- ENCABEZADO -->
            <header class="encabezado">

                <div>
                    <h1>Médicos</h1>
                    <p>Administración y gestión de médicos registrados.</p>
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
                        <h2>Listado de médicos</h2>
                        <p>Consulta y administra la información de los médicos.</p>
                    </div>

                    <a href="registrar.php" class="btn btn-agregar">
                        <i class="bi bi-plus-lg"></i>
                        Agregar médico
                    </a>

                </div>


                <!-- BUSCADOR -->
                <div class="medicos-herramientas">

                    <div class="buscador-medicos">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="buscarMedico"
                            class="form-control"
                            placeholder="Buscar por nombre, SSN o especialidad..."
                        >

                    </div>

                    <span class="total-registros">
                        <i class="bi bi-person-badge"></i>
                        Médicos registrados: 2
                    </span>

                </div>


                <!-- TABLA -->
                <div class="tabla-contenedor">

                    <div class="table-responsive">

                        <table class="table tabla-medicos align-middle">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Médico</th>
                                    <th>SSN</th>
                                    <th>Especialidad</th>
                                    <th>Experiencia</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaMedicos">

                                <!-- REGISTRO 1 -->
                                <tr>

                                    <td>1</td>

                                    <td>
                                        <div class="medico-nombre">
                                            <div class="avatar-medico">CG</div>

                                            <div>
                                                <strong>Carlos González</strong>
                                                <small>López</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>12345678901</td>

                                    <td>
                                        <span class="especialidad">
                                            Cardiología
                                        </span>
                                    </td>

                                    <td>5 años</td>

                                    <td>
                                        <div class="acciones">

                                            <button
                                                type="button"
                                                class="btn btn-detalles"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetalles"
                                                data-id="1"
                                                data-ssn="12345678901"
                                                data-nombre="Carlos"
                                                data-primer-ap="González"
                                                data-segundo-ap="López"
                                                data-especialidad="Cardiología"
                                                data-experiencia="5"
                                            >
                                                <i class="bi bi-eye"></i>
                                                Detalles
                                            </button>

                                            <a
                                                href="editar.php?id=1"
                                                class="btn btn-editar"
                                                title="Editar médico"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button
                                                type="button"
                                                class="btn btn-eliminar"
                                                title="Eliminar médico"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>

                                        </div>
                                    </td>

                                </tr>


                                <!-- REGISTRO 2 -->
                                <tr>

                                    <td>2</td>

                                    <td>
                                        <div class="medico-nombre">
                                            <div class="avatar-medico">ML</div>

                                            <div>
                                                <strong>María López</strong>
                                                <small>Hernández</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>98765432101</td>

                                    <td>
                                        <span class="especialidad">
                                            Pediatría
                                        </span>
                                    </td>

                                    <td>8 años</td>

                                    <td>
                                        <div class="acciones">

                                            <button
                                                type="button"
                                                class="btn btn-detalles"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetalles"
                                                data-id="2"
                                                data-ssn="98765432101"
                                                data-nombre="María"
                                                data-primer-ap="López"
                                                data-segundo-ap="Hernández"
                                                data-especialidad="Pediatría"
                                                data-experiencia="8"
                                            >
                                                <i class="bi bi-eye"></i>
                                                Detalles
                                            </button>

                                            <a
                                                href="editar.php?id=2"
                                                class="btn btn-editar"
                                                title="Editar médico"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button
                                                type="button"
                                                class="btn btn-eliminar"
                                                title="Eliminar médico"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>

                                        </div>
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <!-- PAGINACIÓN VISUAL -->
                    <div class="tabla-pie">

                        <span>Mostrando 2 médicos</span>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Anterior</a>
                                </li>

                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>

                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Siguiente</a>
                                </li>
                            </ul>
                        </nav>

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
                        <h5 class="modal-title">Detalles del médico</h5>
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
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <div>
                            <h5 id="detalleNombre">Nombre del médico</h5>
                            <span id="detalleEspecialidad">Especialidad</span>
                        </div>

                    </div>

                    <div class="detalle-datos">

                        <div class="detalle-item">
                            <span>ID del médico</span>
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
                            <span>Especialidad</span>
                            <strong id="detalleEspecialidadCampo">-</strong>
                        </div>

                        <div class="detalle-item">
                            <span>Años de experiencia</span>
                            <strong id="detalleExperiencia">-</strong>
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

    <!-- FUNCIONALIDAD VISUAL -->
    <script>

        // BUSCADOR VISUAL
        const buscarMedico = document.getElementById('buscarMedico');

        buscarMedico.addEventListener('input', function () {

            const texto = this.value.toLowerCase();

            const filas = document.querySelectorAll('#tablaMedicos tr');

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
            const especialidad = boton.dataset.especialidad;
            const experiencia = boton.dataset.experiencia;

            document.getElementById('detalleId').textContent = id;
            document.getElementById('detalleSSN').textContent = ssn;
            document.getElementById('detalleNombre').textContent = nombre + ' ' + primerAp;
            document.getElementById('detalleEspecialidad').textContent = especialidad;

            document.getElementById('detalleNombreCampo').textContent = nombre;
            document.getElementById('detallePrimerAp').textContent = primerAp;
            document.getElementById('detalleSegundoAp').textContent = segundoAp;
            document.getElementById('detalleEspecialidadCampo').textContent = especialidad;
            document.getElementById('detalleExperiencia').textContent = experiencia + ' años';

        });

    </script>

</body>
</html>
