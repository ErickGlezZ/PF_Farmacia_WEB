<?php

session_start();

require_once __DIR__ . '/../../../backend/config/Autoload.php';

use App\Middleware\AuthMiddleware;

// Solamente el administrador puede registrar usuarios.
AuthMiddleware::requireRole(
    ['Administrador'],
    '../login.php'
);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrar usuario | Farmacias R-X</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../css/dashboard.css">

</head>

<body>

    <div class="container py-5">

        <div class="card shadow-sm mx-auto" style="max-width: 600px;">

            <div class="card-body p-4">

                <h3 class="mb-2">
                    <i class="bi bi-person-plus"></i>
                    Registrar usuario
                </h3>

                <p class="text-secondary">
                    Ingresa los datos para crear una nueva cuenta.
                </p>

                <?php if (isset($_SESSION['mensaje'])): ?>

                    <div class="alert alert-<?= htmlspecialchars($_SESSION['tipo_mensaje'] ?? 'info') ?>">
                        <?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <?php
                    unset($_SESSION['mensaje']);
                    unset($_SESSION['tipo_mensaje']);
                    ?>

                <?php endif; ?>

                <form
                    action="../../scripts/procesar_altas_usuarios.php"
                    method="POST"
                >

                    <div class="mb-3">

                        <label class="form-label">Nombre completo</label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            maxlength="100"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Nombre de usuario</label>

                        <input
                            type="text"
                            name="usuario"
                            class="form-control"
                            minlength="3"
                            maxlength="50"
                            pattern="[a-zA-Z0-9_.-]+"
                            required
                        >

                        <small class="text-secondary">
                            Utiliza letras, números, puntos, guiones o guiones bajos.
                        </small>

                    </div>

                    <div class="mb-3">

                        <label for="rol" class="form-label">
                            Tipo de cuenta
                        </label>

                        <select
                            class="form-select"
                            id="rol"
                            name="rol"
                            required
                        >

                            <option value="" selected disabled>
                                Selecciona el tipo de cuenta
                            </option>

                            <option value="Empleado">
                                Empleado
                            </option>

                            <option value="Administrador">
                                Administrador
                            </option>

                        </select>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">Contraseña</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            minlength="12"
                            required
                        >

                        <small class="text-secondary">
                            Debe contener al menos 12 caracteres.
                        </small>

                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-person-check"></i>
                            Registrar usuario
                        </button>

                        <a href="gestion_usuarios.php" class="btn btn-secondary">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>
</html>
