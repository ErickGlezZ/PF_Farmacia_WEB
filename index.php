<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Farmacias R-X: sistema de gestión de médicos, pacientes y recetas médicas.">
    <title>Farmacias R-X | Gestión inteligente</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="frontend/css/landing.css">

    <!-- Tipografía -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <header class="navbar-wrapper" id="inicio">
        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <a class="navbar-brand brand-logo" href="#inicio">
                    <span class="brand-icon">
                        <i class="bi bi-capsule-pill"></i>
                    </span>
                    <span>
                        <strong>Farmacias <span>R-X</span></strong>
                        <small>Sistema de gestión</small>
                    </span>
                </a>

                
                <!-- Menú para computadoras -->
                <div class="d-none d-lg-flex align-items-center gap-4 ms-auto">
                    <a class="nav-link-custom active" href="#inicio">Inicio</a>
                    <a class="nav-link-custom" href="#nosotros">Nosotros</a>
                    <a class="nav-link-custom" href="#modulos">Módulos</a>
                    <a class="nav-link-custom" href="#beneficios">Beneficios</a>

                    <a href="frontend/pages/login.php" class="btn btn-login">
                        Iniciar sesión
                    </a>

                </div>


                <!-- Menú móvil con Alpine.js -->
                <div class="d-lg-none ms-auto" x-data="{ abierto: false }">
                    <button class="mobile-menu-btn"
                            type="button"
                            @click="abierto = !abierto"
                            :aria-expanded="abierto.toString()"
                            aria-label="Abrir menú">
                        <i class="bi" :class="abierto ? 'bi-x-lg' : 'bi-list'"></i>
                    </button>

                    <div class="mobile-menu" x-show="abierto" x-transition @click.outside="abierto = false">
                        <a href="#inicio" @click="abierto = false">Inicio</a>
                        <a href="#nosotros" @click="abierto = false">Nosotros</a>
                        <a href="#modulos" @click="abierto = false">Módulos</a>
                        <a href="#beneficios" @click="abierto = false">Beneficios</a>
                        
                        <a href="frontend/pages/login.php" class="mobile-login">
                            Iniciar sesión
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>
                </div>

            </div>
        </nav>
    </header>


    <!-- ================= HERO ================= -->
    <main>

        <section class="hero-section">
            <div class="hero-decoration hero-decoration-one"></div>
            <div class="hero-decoration hero-decoration-two"></div>

            <div class="container position-relative">
                <div class="row align-items-center gy-5">

                    <div class="col-lg-6">
                        <div class="hero-content">

                            <div class="hero-label">
                                <span class="status-dot"></span>
                                Gestión farmacéutica en un solo lugar
                            </div>

                            <h1>
                                La gestión de tu farmacia,
                                <span>más sencilla y organizada.</span>
                            </h1>

                            <p class="hero-description">
                                Administra médicos, pacientes y recetas desde una
                                plataforma diseñada para facilitar tus actividades,
                                organizar tu información y optimizar tu trabajo diario.
                            </p>

                            <div class="hero-buttons">
                                <a href="frontend/pages/login.php" class="btn btn-primary-custom">
                                    Acceder al sistema
                                    <i class="bi bi-arrow-up-right ms-2"></i>
                                </a>

                                <a href="#modulos" class="btn btn-outline-custom">
                                    Conocer módulos
                                    <i class="bi bi-chevron-down ms-2"></i>
                                </a>
                            </div>

                            <div class="hero-trust">
                                <div class="trust-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <strong>Información organizada</strong>
                                    <span>Control centralizado de tus registros</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Representación visual del sistema -->
                    <div class="col-lg-6">
                        <div class="hero-visual">

                            <div class="visual-glow"></div>

                            <div class="dashboard-preview">

                                <div class="preview-header">
                                    <div class="preview-brand">
                                        <span class="preview-logo">
                                            <i class="bi bi-capsule-pill"></i>
                                        </span>
                                        <div>
                                            <strong>Farmacias R-X</strong>
                                            <small>Panel de administración</small>
                                        </div>
                                    </div>

                                    <span class="preview-indicator">
                                        <i class="bi bi-circle-fill"></i> Sistema
                                    </span>
                                </div>

                                <div class="preview-welcome">
                                    <small>BIENVENIDO AL SISTEMA</small>
                                    <h4>Tu farmacia, bajo control.</h4>
                                    <p>Administra tus registros fácilmente.</p>
                                </div>

                                <div class="preview-cards">

                                    <div class="preview-card">
                                        <div class="preview-card-icon icon-blue">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <div>
                                            <span>Médicos</span>
                                            <strong>Gestión</strong>
                                        </div>
                                        <i class="bi bi-arrow-up-right preview-arrow"></i>
                                    </div>

                                    <div class="preview-card">
                                        <div class="preview-card-icon icon-green">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div>
                                            <span>Pacientes</span>
                                            <strong>Registros</strong>
                                        </div>
                                        <i class="bi bi-arrow-up-right preview-arrow"></i>
                                    </div>

                                    <div class="preview-card">
                                        <div class="preview-card-icon icon-purple">
                                            <i class="bi bi-prescription2"></i>
                                        </div>
                                        <div>
                                            <span>Recetas</span>
                                            <strong>Control</strong>
                                        </div>
                                        <i class="bi bi-arrow-up-right preview-arrow"></i>
                                    </div>

                                </div>

                                <div class="preview-footer">
                                    <i class="bi bi-shield-lock-fill"></i>
                                    Información centralizada en una plataforma
                                </div>

                            </div>

                            <div class="floating-card floating-card-top">
                                <span class="floating-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </span>
                                <div>
                                    <strong>Organización</strong>
                                    <small>Todo en un mismo lugar</small>
                                </div>
                            </div>

                            <div class="floating-card floating-card-bottom">
                                <span class="floating-icon floating-icon-blue">
                                    <i class="bi bi-clipboard2-pulse"></i>
                                </span>
                                <div>
                                    <strong>Gestión sencilla</strong>
                                    <small>Procesos más ordenados</small>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="hero-bottom">
                <div class="container">
                    <a href="#nosotros" class="scroll-indicator">
                        Descubre nuestra plataforma
                        <i class="bi bi-arrow-down"></i>
                    </a>
                </div>
            </div>
        </section>


        <!-- ================= NOSOTROS ================= -->
        <section class="about-section section-padding" id="nosotros">
            <div class="container">

                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <span class="section-label">CONÓCENOS</span>
                        <h2 class="section-title">
                            Tecnología al servicio de una
                            <span>mejor gestión.</span>
                        </h2>
                        <p class="section-description">
                            Farmacias R-X es una plataforma web enfocada en facilitar
                            la administración de la información relacionada con médicos,
                            pacientes y recetas médicas, mediante herramientas que
                            permiten mantener los registros organizados y accesibles.
                        </p>
                    </div>
                </div>

                <div class="row g-4 about-features">

                    <div class="col-md-4">
                        <div class="about-feature">
                            <div class="feature-icon feature-blue">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <h5>Información centralizada</h5>
                            <p>
                                Mantén los datos de tu sistema organizados
                                desde una misma plataforma.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="about-feature">
                            <div class="feature-icon feature-green">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <h5>Procesos simplificados</h5>
                            <p>
                                Facilita el registro, consulta, edición y
                                eliminación de información.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="about-feature">
                            <div class="feature-icon feature-purple">
                                <i class="bi bi-laptop"></i>
                            </div>
                            <h5>Acceso desde la web</h5>
                            <p>
                                Consulta y administra tus registros desde
                                un navegador compatible.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ================= MODULOS ================= -->
        <section class="modules-section section-padding" id="modulos">
            <div class="container">

                <div class="section-heading">
                    <div>
                        <span class="section-label">NUESTRA PLATAFORMA</span>
                        <h2 class="section-title">
                            Todo lo que necesitas,
                            <span>en tres módulos.</span>
                        </h2>
                    </div>

                    <p class="section-description heading-description">
                        Cada módulo está diseñado para facilitar una parte
                        importante de la administración de tu farmacia.
                    </p>
                </div>

                <div class="row g-4">

                    <!-- Médicos -->
                    <div class="col-lg-4 col-md-6">
                        <article class="module-card">
                            <div class="module-card-top">
                                <div class="module-icon module-blue">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <span class="module-number">01</span>
                            </div>

                            <h4>Gestión de médicos</h4>
                            <p>
                                Administra la información de los profesionales
                                médicos, sus datos personales, especialidades
                                y años de experiencia.
                            </p>

                            <div class="module-divider"></div>

                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Registro y consulta de médicos
                            </div>
                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Actualización de información
                            </div>
                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Organización de especialidades
                            </div>
                        </article>
                    </div>

                    <!-- Pacientes -->
                    <div class="col-lg-4 col-md-6">
                        <article class="module-card module-card-featured">
                            <div class="module-card-top">
                                <div class="module-icon module-green">
                                    <i class="bi bi-people"></i>
                                </div>
                                <span class="module-number">02</span>
                            </div>

                            <h4>Gestión de pacientes</h4>
                            <p>
                                Organiza los datos de tus pacientes y relaciona
                                cada registro con su médico de cabecera para
                                facilitar su administración.
                            </p>

                            <div class="module-divider"></div>

                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Registro de pacientes
                            </div>
                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Consulta y edición de datos
                            </div>
                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Asociación con médicos
                            </div>
                        </article>
                    </div>

                    <!-- Recetas -->
                    <div class="col-lg-4 col-md-6">
                        <article class="module-card">
                            <div class="module-card-top">
                                <div class="module-icon module-purple">
                                    <i class="bi bi-prescription2"></i>
                                </div>
                                <span class="module-number">03</span>
                            </div>

                            <h4>Gestión de recetas</h4>
                            <p>
                                Registra y consulta las recetas médicas,
                                relacionándolas con el médico y el paciente
                                correspondiente.
                            </p>

                            <div class="module-divider"></div>

                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Registro de recetas médicas
                            </div>
                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Control de medicamentos y cantidades
                            </div>
                            <div class="module-detail">
                                <i class="bi bi-check2-circle"></i>
                                Consulta de indicaciones
                            </div>
                        </article>
                    </div>

                </div>
            </div>
        </section>


        <!-- ================= BENEFICIOS ================= -->
        <section class="benefits-section section-padding" id="beneficios">
            <div class="container">

                <div class="row align-items-center gy-5">

                    <div class="col-lg-6">
                        <div class="benefits-visual">
                            <div class="benefit-main-card">
                                <div class="benefit-main-icon">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                                <span>FARMACIAS R-X</span>
                                <h3>Una gestión más clara y eficiente.</h3>
                                <p>
                                    Herramientas pensadas para mantener
                                    tu información organizada.
                                </p>

                                <div class="benefit-progress">
                                    <div>
                                        <span>Organización</span>
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                </div>

                                <div class="benefit-progress">
                                    <div>
                                        <span>Accesibilidad</span>
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-two" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="benefit-decoration"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="benefits-content">
                            <span class="section-label">¿POR QUÉ UTILIZARLO?</span>

                            <h2 class="section-title">
                                Una plataforma pensada para
                                <span>simplificar tu trabajo.</span>
                            </h2>

                            <p class="section-description">
                                Centraliza las operaciones principales de
                                administración y consulta de información
                                en un entorno sencillo y accesible.
                            </p>

                            <div class="benefit-list">

                                <div class="benefit-item">
                                    <div class="benefit-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <div>
                                        <h6>Administración organizada</h6>
                                        <p>Gestiona médicos, pacientes y recetas desde un mismo sistema.</p>
                                    </div>
                                </div>

                                <div class="benefit-item">
                                    <div class="benefit-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <div>
                                        <h6>Operaciones intuitivas</h6>
                                        <p>Realiza altas, bajas, consultas y modificaciones de registros.</p>
                                    </div>
                                </div>

                                <div class="benefit-item">
                                    <div class="benefit-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <div>
                                        <h6>Información relacionada</h6>
                                        <p>Consulta los vínculos entre médicos, pacientes y recetas.</p>
                                    </div>
                                </div>

                                <div class="benefit-item">
                                    <div class="benefit-check">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <div>
                                        <h6>Interfaz adaptable</h6>
                                        <p>Utiliza la plataforma desde distintos tamaños de pantalla.</p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ================= CTA ================= -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">

                    <div class="cta-icon">
                        <i class="bi bi-capsule-pill"></i>
                    </div>

                    <h2>Comienza a gestionar tu farmacia de una mejor manera.</h2>

                    <p>
                        Ingresa a Farmacias R-X y descubre una forma más
                        organizada de administrar tu información.
                    </p>

                    <a href="frontend/pages/login.php" class="btn btn-cta">
                        Iniciar sesión
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                </div>
            </div>
        </section>

    </main>


    <!-- ================= FOOTER ================= -->
    <footer class="footer-section">
        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-5">
                    <a class="navbar-brand brand-logo footer-brand" href="#inicio">
                        <span class="brand-icon">
                            <i class="bi bi-capsule-pill"></i>
                        </span>
                        <span>
                            <strong>Farmacias <span>R-X</span></strong>
                            <small>Sistema de gestión</small>
                        </span>
                    </a>

                    <p class="footer-description">
                        Una plataforma web para organizar y facilitar
                        la administración de médicos, pacientes y recetas.
                    </p>
                </div>

                <div class="col-6 col-lg-2">
                    <h6>Explorar</h6>
                    <a href="#inicio">Inicio</a>
                    <a href="#nosotros">Nosotros</a>
                    <a href="#modulos">Módulos</a>
                    <a href="#beneficios">Beneficios</a>
                </div>

                <div class="col-6 col-lg-2">
                    <h6>Plataforma</h6>
                    <a href="frontend/pages/login.php">Iniciar sesión</a>
                    <a href="#modulos">Médicos</a>
                    <a href="#modulos">Pacientes</a>
                    <a href="#modulos">Recetas</a>
                </div>

                <div class="col-lg-3">
                    <h6>Acceso al sistema</h6>
                    <p class="footer-access-text">
                        Ingresa a tu cuenta para administrar la información
                        de la plataforma.
                    </p>
                    <a href="frontend/pages/login.php" class="footer-login">
                        Acceder ahora <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>

            </div>

            <div class="footer-bottom">
                <span>© <?php echo date('Y'); ?> Farmacias R-X. Todos los derechos reservados.</span>
                <a href="#inicio">
                    Volver arriba <i class="bi bi-arrow-up"></i>
                </a>
            </div>

        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



