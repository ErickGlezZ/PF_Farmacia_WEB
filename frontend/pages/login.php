<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;

// Iniciar sesión con la configuración de seguridad
Sesion::iniciar();

// Si ya existe una sesión, enviar al dashboard
if (
    isset($_SESSION['id_usuario']) &&
    isset($_SESSION['usuario_autenticado'])
) {
    header('Location: dashboard.php');
    exit;
}

// Recuperar mensaje de error, si existe
$mensaje = $_SESSION['mensaje_login'] ?? '';
unset($_SESSION['mensaje_login']);

// Crear token para proteger el formulario
if (empty($_SESSION['csrf_login'])) {
    $_SESSION['csrf_login'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Farmacias R-X</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS propio -->
    <link rel="stylesheet" href="../css/login.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body>

    <main class="login-container">

        <!-- Panel informativo -->
        <section class="login-information">

            <a href="../../index.php" class="brand">
                <span class="brand-icon">
                    <i class="bi bi-plus-lg"></i>
                </span>

                <span class="brand-text">
                    <strong>Farmacias R-X</strong>
                    <small>Sistema de gestión farmacéutica</small>
                </span>
            </a>

            <div class="information-content">

                <span class="information-label">
                    <i class="bi bi-shield-check"></i>
                    PLATAFORMA DE GESTIÓN
                </span>

                <h1>
                    Bienvenido de nuevo.
                    <span>Tu farmacia, bajo control.</span>
                </h1>

                <p>
                    Administra la información de médicos, pacientes
                    y recetas desde una plataforma diseñada para
                    facilitar el trabajo de tu farmacia.
                </p>

                <div class="information-features">

                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Información organizada</span>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Acceso para personal autorizado</span>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestión centralizada</span>
                    </div>

                </div>

            </div>

            <div class="information-footer">
                <span>© 2026 Farmacias R-X</span>
                <span>Gestión · Organización · Confianza</span>
            </div>

        </section>


        <!-- Panel del formulario -->
        <section class="login-form-section">

            <div class="login-form-wrapper">

                <div class="mobile-brand">
                    <span class="brand-icon">
                        <i class="bi bi-plus-lg"></i>
                    </span>
                    <strong>Farmacias R-X</strong>
                </div>

                <div class="form-heading">

                    <span class="form-label">
                        ACCESO AL SISTEMA
                    </span>

                    <h2>Iniciar sesión</h2>

                    <p>
                        Ingresa tus credenciales para continuar.
                    </p>

                </div>


                <!-- Formulario visual, todavía sin procesamiento -->
                <form
                    method="POST"
                    action="../scripts/procesar_login.php"
                    autocomplete="on"
                >
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_login'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <?php if (!empty($mensaje)): ?>

                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    <?php endif; ?>




                    <div class="form-group">

                        <label for="usuario">
                            Nombre de usuario
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-person input-icon"></i>

                            <input
                                type="text"
                                id="usuario"
                                name="usuario"
                                placeholder="Ingresa tu usuario"
                                autocomplete="username"
                                required
                            >

                        </div>

                    </div>


                    <div class="form-group">

                        <label for="password">
                            Contraseña
                        </label>

                        <div
                            class="input-wrapper"
                            x-data="{ mostrarPassword: false }"
                        >

                            <i class="bi bi-lock input-icon"></i>

                            <input
                                :type="mostrarPassword ? 'text' : 'password'"
                                id="password"
                                name="password"
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                @click="mostrarPassword = !mostrarPassword"
                                :aria-label="mostrarPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                            >
                                <i
                                    :class="mostrarPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"
                                ></i>
                            </button>

                        </div>

                    </div>

                    
                    <!-- CAPTCHA Cloudflare Turnstile -->
                    <div class="d-flex justify-content-center my-4">

                        <div
                            class="cf-turnstile"
                            data-sitekey="0x4AAAAAAGSxjgo_3E3esrPa"
                            data-theme="light"
                        ></div>

                    </div>
                    


                    <div class="forgot-password">
                        <a href="recuperar_password.php">
                            ¿Olvidaste tu contraseña?
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>


                    <button type="submit" class="btn-login">
                        Entrar al sistema
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>


                <div class="login-security">

                    <i class="bi bi-shield-lock-fill"></i>

                    <span>
                        Acceso exclusivo para usuarios autorizados.
                    </span>

                </div>


                <div class="back-home">
                    <a href="../../index.php">
                        <i class="bi bi-arrow-left"></i>
                        Volver al inicio
                    </a>
                </div>

            </div>

        </section>

    </main>

</body>
</html>
