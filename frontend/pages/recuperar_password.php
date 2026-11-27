<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\facade\FarmaciaFacade;

Sesion::iniciar();

$mensaje = $_SESSION['mensaje_recuperacion'] ?? '';
$tipoMensaje = $_SESSION['tipo_mensaje_recuperacion'] ?? 'info';

unset(
    $_SESSION['mensaje_recuperacion'],
    $_SESSION['tipo_mensaje_recuperacion']
);

$token = trim($_GET['token'] ?? '');

$modoRestablecer = false;

if ($token !== '') {

    try {

        $farmacia = new FarmaciaFacade();

        $datosToken = $farmacia->validarTokenRecuperacion($token);

        if ($datosToken !== null) {
            $modoRestablecer = true;
        } else {
            $mensaje = 'El enlace de recuperación no es válido, expiró o ya fue utilizado.';
            $tipoMensaje = 'danger';
        }

    } catch (\Throwable $e) {

        error_log('Error al validar recuperación: ' . $e->getMessage());

        $mensaje = 'No fue posible validar el enlace. Intenta nuevamente.';
        $tipoMensaje = 'danger';
    }
}

// Generar token CSRF para el formulario correspondiente.
if ($modoRestablecer) {

    if (empty($_SESSION['csrf_restaurar_password'])) {
        $_SESSION['csrf_restaurar_password'] = bin2hex(random_bytes(32));
    }

} else {

    if (empty($_SESSION['csrf_recuperacion'])) {
        $_SESSION['csrf_recuperacion'] = bin2hex(random_bytes(32));
    }

}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperar contraseña | Farmacias R-X</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Alpine.js -->
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>

    <style>

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            padding: 20px;
        }

        .contenedor-recuperacion {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
        }

        .icono-recuperacion {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #e8eef5;
            color: #1e3a56;
            font-size: 30px;
        }

        .titulo {
            color: #1e293b;
            font-weight: 800;
            text-align: center;
            font-size: 23px;
        }

        .descripcion {
            color: #64748b;
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .form-control {
            padding: 12px;
            border-radius: 9px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #647d96;
            box-shadow: 0 0 0 0.2rem rgba(100,125,150,0.15);
        }

        .btn-principal {
            width: 100%;
            padding: 12px;
            background: #1e3a56;
            border: none;
            color: white;
            border-radius: 9px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 12px;
        }

        .btn-principal:hover {
            background: #294c6b;
            color: white;
        }

        .btn-principal:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .contenedor-password {
            position: relative;
        }

        .contenedor-password .form-control {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            font-size: 17px;
        }

        .toggle-password:hover {
            color: #1e3a56;
        }

        .enlace-login {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
        }

        .enlace-login:hover {
            color: #1e3a56;
        }

    </style>

</head>

<body>

    <div
        class="contenedor-recuperacion"
        x-data="{
            mostrarPassword: false,
            mostrarConfirmacion: false,
            enviando: false
        }"
    >

        <div class="icono-recuperacion">
            <i class="bi bi-shield-lock"></i>
        </div>

        <?php if ($modoRestablecer): ?>

            <h1 class="titulo">Nueva contraseña</h1>

            <p class="descripcion">
                Introduce tu nueva contraseña para recuperar el acceso
                a tu cuenta.
            </p>

        <?php else: ?>

            <h1 class="titulo">¿Olvidaste tu contraseña?</h1>

            <p class="descripcion">
                Escribe tu nombre de usuario para iniciar el proceso
                de recuperación de tu cuenta.
            </p>

        <?php endif; ?>


        <?php if ($mensaje !== ''): ?>

            <div class="alert alert-<?= htmlspecialchars($tipoMensaje, ENT_QUOTES, 'UTF-8') ?> small">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
            </div>

        <?php endif; ?>


        <?php if ($modoRestablecer): ?>

            <form
                action="../scripts/procesar_restaurar_password.php"
                method="POST"
                @submit="enviando = true"
            >

                <input
                    type="hidden"
                    name="token"
                    value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>"
                >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_restaurar_password'], ENT_QUOTES, 'UTF-8') ?>"
                >

                <div class="mb-3">

                    <label class="form-label">
                        Nueva contraseña
                    </label>

                    <div class="contenedor-password">

                        <input
                            :type="mostrarPassword ? 'text' : 'password'"
                            name="password"
                            class="form-control"
                            placeholder="Mínimo 8 caracteres"
                            minlength="8"
                            maxlength="72"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            @click="mostrarPassword = !mostrarPassword"
                            :aria-label="mostrarPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                        >
                            <i :class="mostrarPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Confirmar contraseña
                    </label>

                    <div class="contenedor-password">

                        <input
                            :type="mostrarConfirmacion ? 'text' : 'password'"
                            name="confirmacion"
                            class="form-control"
                            placeholder="Repite tu contraseña"
                            minlength="8"
                            maxlength="72"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            @click="mostrarConfirmacion = !mostrarConfirmacion"
                            :aria-label="mostrarConfirmacion ? 'Ocultar confirmación' : 'Mostrar confirmación'"
                        >
                            <i :class="mostrarConfirmacion ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-principal"
                    :disabled="enviando"
                >
                    <template x-if="!enviando">
                        <span>
                            <i class="bi bi-check-circle me-2"></i>
                            Restablecer contraseña
                        </span>
                    </template>

                    <template x-if="enviando">
                        <span>
                            <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                            Procesando...
                        </span>
                    </template>
                </button>

            </form>

        <?php else: ?>

            <form
                action="../scripts/procesar_recuperacion.php"
                method="POST"
                @submit="enviando = true"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_recuperacion'], ENT_QUOTES, 'UTF-8') ?>"
                >

                <div class="mb-3">

                    <label class="form-label">
                        Nombre de usuario
                    </label>

                    <input
                        type="text"
                        name="usuario"
                        class="form-control"
                        placeholder="Ingresa tu usuario"
                        maxlength="50"
                        autocomplete="username"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-principal"
                    :disabled="enviando"
                >
                    <template x-if="!enviando">
                        <span>
                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Continuar
                        </span>
                    </template>

                    <template x-if="enviando">
                        <span>
                            <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                            Procesando...
                        </span>
                    </template>
                </button>

            </form>

        <?php endif; ?>


        <a href="login.php" class="enlace-login">
            <i class="bi bi-arrow-left me-1"></i>
            Volver al inicio de sesión
        </a>

    </div>

</body>

</html>
