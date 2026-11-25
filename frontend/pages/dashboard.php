<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Middleware\AuthMiddleware;

// Proteger el dashboard
AuthMiddleware::requireRole(
    ['Administrador', 'Empleado'],
    'login.php'
);

// Obtener información del usuario autenticado
$nombreUsuario = htmlspecialchars(
    $_SESSION['nombre_usuario'] ?? 'Usuario',
    ENT_QUOTES,
    'UTF-8'
);

$rolUsuario = htmlspecialchars(
    $_SESSION['rol'] ?? 'Empleado',
    ENT_QUOTES,
    'UTF-8'
);

$esAdministrador = ($_SESSION['rol'] ?? '') === 'Administrador';

date_default_timezone_set('America/Mexico_City');

$fechaActual = date('d/m/Y');

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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <div class="contenedor">

        <!-- MENÚ LATERAL -->
        <aside class="sidebar">

            <div class="sidebar-logo">
                <span class="logo-icon">
                    <i class="bi bi-capsule-pill"></i>
                </span>

                <span class="logo-text">
                    <strong>Farmacias R-X</strong>
                    <small>Sistema de gestión</small>
                </span>
            </div>


            <div class="sidebar-menu">

                <p class="menu-titulo">MENÚ PRINCIPAL</p>

                <a href="dashboard.php" class="menu-enlace activo">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Panel principal</span>
                </a>

                <a href="medicos/consultar.php" class="menu-enlace">
                    <i class="bi bi-person-badge"></i>
                    <span>Médicos</span>
                </a>

                <a href="pacientes/consultar.php" class="menu-enlace">
                    <i class="bi bi-people"></i>
                    <span>Pacientes</span>
                </a>

                <a href="recetas/consultar.php" class="menu-enlace">
                    <i class="bi bi-file-medical"></i>
                    <span>Recetas</span>
                </a>


                <?php if ($esAdministrador): ?>

                <p class="menu-titulo menu-titulo-secundario">
                    ADMINISTRACIÓN
                </p>

                <a href="usuarios/gestion_usuarios.php" class="menu-enlace">
                    <i class="bi bi-person-gear"></i>
                    <span>Gestión de usuarios</span>
                </a>

            <?php endif; ?>

            </div>


            <!-- INFORMACIÓN DEL USUARIO -->
            <div class="sidebar-user">

                <div class="user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div class="user-details">
                    <strong><?php echo $nombreUsuario; ?></strong>
                    <span><?php echo $rolUsuario; ?></span>
                </div>

                <span class="user-status" title="Sesión activa"></span>

            </div>


            <!-- CERRAR SESIÓN -->
            <div class="sidebar-footer">

                <form action="../scripts/procesar_logout.php" method="POST">

                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Cerrar sesión</span>
                    </button>

                </form>

            </div>

        </aside>


        <!-- CONTENIDO PRINCIPAL -->
        <main class="contenido">

            <!-- ENCABEZADO -->
            <header class="encabezado">

                <div class="encabezado-titulo">

                    <span class="breadcrumb-text">
                        FARMACIAS R-X / INICIO
                    </span>

                    <h1>Panel principal</h1>

                    <p>
                        Consulta y administra la información general de tu farmacia.
                    </p>

                </div>


                <div class="encabezado-derecha">

                    <div class="fecha-actual">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo $fechaActual; ?></span>
                    </div>

                    <div class="usuario-info">

                        <div class="usuario-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div>
                            <strong><?php echo $nombreUsuario; ?></strong>
                            <span><?php echo $rolUsuario; ?></span>
                        </div>

                    </div>

                </div>

            </header>


            <!-- BIENVENIDA -->
            <section class="bienvenida">

                <div class="bienvenida-contenido">

                    <span class="bienvenida-etiqueta">
                        <i class="bi bi-stars"></i>
                        ESPACIO DE TRABAJO
                    </span>

                    <h2>
                        ¡Hola, <?php echo $nombreUsuario; ?>!
                    </h2>

                    <p>
                        Aquí puedes consultar la información de médicos,
                        pacientes y recetas, y mantener organizada la operación
                        de tu farmacia.
                    </p>

                    <a href="medicos/consultar.php" class="btn-bienvenida">
                        Explorar el sistema
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

                <div class="bienvenida-ilustracion">
                    <div class="ilustracion-circulo circulo-uno"></div>
                    <div class="ilustracion-circulo circulo-dos"></div>

                    <div class="ilustracion-icono">
                        <i class="bi bi-capsule-pill"></i>
                    </div>

                    <i class="bi bi-heart-pulse icono-flotante icono-flotante-uno"></i>
                    <i class="bi bi-clipboard2-pulse icono-flotante icono-flotante-dos"></i>
                </div>

            </section>


            <!-- INDICADORES -->
            <section class="seccion-estadisticas">

                <div class="seccion-encabezado">

                    <div>
                        <span class="subtitulo-seccion">VISTA GENERAL</span>
                        <h2>Resumen del sistema</h2>
                        <p>Indicadores generales de los registros almacenados.</p>
                    </div>

                    <span class="estado-indicadores">
                        <i class="bi bi-clock-history"></i>
                        Pendientes de conexión
                    </span>

                </div>


                <div class="estadisticas-grid">

                    <!-- MÉDICOS -->
                    <article class="tarjeta-estadistica">

                        <div class="estadistica-superior">
                            <div class="tarjeta-icono icono-medicos">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <span class="estadistica-etiqueta">MÉDICOS</span>
                        </div>

                        <div class="estadistica-datos">
                            <h3>--</h3>
                            <p>Total de médicos registrados</p>
                        </div>

                        <div class="estadistica-pie">
                            <a href="medicos/consultar.php">
                                Consultar médicos
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>

                    </article>


                    <!-- PACIENTES -->
                    <article class="tarjeta-estadistica">

                        <div class="estadistica-superior">
                            <div class="tarjeta-icono icono-pacientes">
                                <i class="bi bi-people"></i>
                            </div>

                            <span class="estadistica-etiqueta">PACIENTES</span>
                        </div>

                        <div class="estadistica-datos">
                            <h3>--</h3>
                            <p>Total de pacientes registrados</p>
                        </div>

                        <div class="estadistica-pie">
                            <a href="pacientes/consultar.php">
                                Consultar pacientes
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>

                    </article>


                    <!-- RECETAS -->
                    <article class="tarjeta-estadistica">

                        <div class="estadistica-superior">
                            <div class="tarjeta-icono icono-recetas">
                                <i class="bi bi-file-medical"></i>
                            </div>

                            <span class="estadistica-etiqueta">RECETAS</span>
                        </div>

                        <div class="estadistica-datos">
                            <h3>--</h3>
                            <p>Total de recetas registradas</p>
                        </div>

                        <div class="estadistica-pie">
                            <a href="recetas/consultar.php">
                                Consultar recetas
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>

                    </article>

                </div>

            </section>


            <!-- RELACIÓN ENTRE LOS MÓDULOS -->
            <section class="seccion-relaciones">

                <div class="seccion-encabezado">

                    <div>
                        <span class="subtitulo-seccion">ORGANIZACIÓN DE DATOS</span>
                        <h2>Relación entre módulos</h2>
                        <p>
                            Visualización conceptual de la información administrada.
                        </p>
                    </div>

                    <span class="etiqueta-relaciones">
                        <i class="bi bi-diagram-3"></i>
                        Estructura del sistema
                    </span>

                </div>


                <div class="relaciones-contenedor">

                    <!-- MÉDICOS -->
                    <article class="relacion-card">

                        <div class="relacion-icono relacion-medico">
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <h3>Médicos</h3>

                        <p>
                            Información de los profesionales médicos registrados.
                        </p>

                        <span class="relacion-tipo">
                            Módulo de profesionales
                        </span>

                        <a href="medicos/consultar.php">
                            Ver módulo
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </article>


                    <div class="relacion-conector">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Información relacionada</span>
                    </div>


                    <!-- PACIENTES -->
                    <article class="relacion-card">

                        <div class="relacion-icono relacion-paciente">
                            <i class="bi bi-people"></i>
                        </div>

                        <h3>Pacientes</h3>

                        <p>
                            Información personal y datos de los pacientes registrados.
                        </p>

                        <span class="relacion-tipo">
                            Módulo de pacientes
                        </span>

                        <a href="pacientes/consultar.php">
                            Ver módulo
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </article>


                    <div class="relacion-conector">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Información relacionada</span>
                    </div>


                    <!-- RECETAS -->
                    <article class="relacion-card">

                        <div class="relacion-icono relacion-receta">
                            <i class="bi bi-file-medical"></i>
                        </div>

                        <h3>Recetas</h3>

                        <p>
                            Registros de recetas médicas y su información asociada.
                        </p>

                        <span class="relacion-tipo">
                            Módulo de recetas
                        </span>

                        <a href="recetas/consultar.php">
                            Ver módulo
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </article>

                </div>


                <div class="relaciones-nota">
                    <i class="bi bi-info-circle"></i>

                    <p>
                        Esta representación es conceptual. Las relaciones
                        definitivas y sus indicadores se conectarán de acuerdo
                        con las claves foráneas de la base de datos.
                    </p>
                </div>

            </section>


            <!-- ACCESOS RÁPIDOS -->
            <section class="seccion-accesos">

                <div class="seccion-encabezado">

                    <div>
                        <span class="subtitulo-seccion">ACCIONES FRECUENTES</span>
                        <h2>Accesos rápidos</h2>
                        <p>Ingresa directamente a las operaciones principales.</p>
                    </div>

                </div>


                <div class="accesos-grid">

                    <a href="medicos/registrar.php" class="tarjeta-acceso">

                        <div class="acceso-icono acceso-medico">
                            <i class="bi bi-person-plus"></i>
                        </div>

                        <div class="acceso-contenido">
                            <h3>Registrar médico</h3>
                            <p>Agregar un profesional al sistema.</p>
                        </div>

                        <i class="bi bi-arrow-up-right acceso-flecha"></i>

                    </a>


                    <a href="pacientes/registrar.php" class="tarjeta-acceso">

                        <div class="acceso-icono acceso-paciente">
                            <i class="bi bi-person-add"></i>
                        </div>

                        <div class="acceso-contenido">
                            <h3>Registrar paciente</h3>
                            <p>Agregar un nuevo paciente.</p>
                        </div>

                        <i class="bi bi-arrow-up-right acceso-flecha"></i>

                    </a>


                    <a href="recetas/registrar.php" class="tarjeta-acceso">

                        <div class="acceso-icono acceso-receta">
                            <i class="bi bi-file-earmark-plus"></i>
                        </div>

                        <div class="acceso-contenido">
                            <h3>Registrar receta</h3>
                            <p>Crear un nuevo registro de receta.</p>
                        </div>

                        <i class="bi bi-arrow-up-right acceso-flecha"></i>

                    </a>

                </div>

            </section>


            <!-- ACTIVIDAD RECIENTE -->
            <section class="seccion-actividad">

                <div class="seccion-encabezado">

                    <div>
                        <span class="subtitulo-seccion">SEGUIMIENTO</span>
                        <h2>Actividad reciente</h2>
                        <p>Espacio preparado para consultar los últimos movimientos.</p>
                    </div>

                </div>


                <div class="actividad-panel">

                    <div class="actividad-vacia-icono">
                        <i class="bi bi-clipboard2-data"></i>
                    </div>

                    <div class="actividad-vacia-texto">
                        <h3>Próximamente tendrás esta información aquí</h3>

                        <p>
                            Se podrán mostrar los últimos médicos, pacientes y
                            recetas registrados o modificados, junto con la fecha
                            y el usuario que realizó cada operación.
                        </p>
                    </div>

                    <span class="actividad-etiqueta">
                        <i class="bi bi-hourglass-split"></i>
                        En preparación
                    </span>

                </div>

            </section>


            <!-- PIE DE PÁGINA -->
            <footer class="dashboard-footer">

                <span>© 2026 Farmacias R-X</span>

                <span>
                    Sistema de gestión farmacéutica
                    <i class="bi bi-heart-pulse"></i>
                </span>

            </footer>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/sesion.js"></script>

</body>
</html>
