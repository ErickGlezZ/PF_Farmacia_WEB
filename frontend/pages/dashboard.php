
<?php
// Por ahora, los indicadores son provisionales.
// Después los consultaremos directamente desde MySQL.
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Farmacias R-X</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/dashboard.css">
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

                <a href="dashboard.php" class="menu-enlace activo">
                    <i class="bi bi-house-door"></i>
                    <span>Inicio</span>
                </a>

                <a href="medicos/consultar.php" class="menu-enlace">
                    <i class="bi bi-person-badge"></i>
                    <span>Médicos</span>
                </a>

                <a href="pacientes/listar.php" class="menu-enlace">
                    <i class="bi bi-people"></i>
                    <span>Pacientes</span>
                </a>

                <a href="recetas/listar.php" class="menu-enlace">
                    <i class="bi bi-file-medical"></i>
                    <span>Recetas</span>
                </a>

            </div>

            <div class="sidebar-footer">

                <a href="login.php" class="menu-enlace">
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
                    <h1>Panel principal</h1>
                    <p>Resumen general del sistema de farmacias.</p>
                </div>

                <div class="usuario-info">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </div>

            </header>


            <!-- INDICADORES -->
            <section class="seccion-estadisticas">

                <h2>Resumen general</h2>

                <div class="row g-4">

                    <!-- MÉDICOS -->
                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="tarjeta-estadistica">

                            <div class="tarjeta-icono icono-medicos">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <div>
                                <p>Total de médicos</p>
                                <h3>--</h3>
                                <span>Registros en el sistema</span>
                            </div>

                        </div>

                    </div>


                    <!-- PACIENTES -->
                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="tarjeta-estadistica">

                            <div class="tarjeta-icono icono-pacientes">
                                <i class="bi bi-people"></i>
                            </div>

                            <div>
                                <p>Total de pacientes</p>
                                <h3>--</h3>
                                <span>Registros en el sistema</span>
                            </div>

                        </div>

                    </div>


                    <!-- RECETAS -->
                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="tarjeta-estadistica">

                            <div class="tarjeta-icono icono-recetas">
                                <i class="bi bi-file-medical"></i>
                            </div>

                            <div>
                                <p>Total de recetas</p>
                                <h3>--</h3>
                                <span>Registros en el sistema</span>
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <!-- ACCESOS RÁPIDOS -->
            <section class="seccion-accesos">

                <h2>Accesos rápidos</h2>

                <div class="row g-4">

                    <div class="col-12 col-md-4">

                        <div class="tarjeta-acceso">

                            <i class="bi bi-person-plus"></i>

                            <h3>Registrar médico</h3>

                            <p>Agregar un nuevo médico al sistema.</p>

                            <a href="medicos/registrar.php">
                                Ir al registro
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </div>


                    <div class="col-12 col-md-4">

                        <div class="tarjeta-acceso">

                            <i class="bi bi-person-plus"></i>

                            <h3>Registrar paciente</h3>

                            <p>Agregar un nuevo paciente al sistema.</p>

                            <a href="pacientes/registrar.php">
                                Ir al registro
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </div>


                    <div class="col-12 col-md-4">

                        <div class="tarjeta-acceso">

                            <i class="bi bi-file-earmark-plus"></i>

                            <h3>Registrar receta</h3>

                            <p>Agregar una nueva receta médica.</p>

                            <a href="recetas/registrar.php">
                                Ir al registro
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </section>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
