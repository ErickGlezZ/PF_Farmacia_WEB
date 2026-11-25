<?php

require_once __DIR__ . '../../../../backend/config/Autoload.php';

use App\Middleware\AuthMiddleware;
use App\facade\FarmaciaFacade;

// Solamente administrador.
AuthMiddleware::requireRole(
    ['Administrador'],
    '../login.php'
);

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: gestion_usuarios.php');
    exit;
}

$id = (int) $_GET['id'];

$farmacia = new FarmaciaFacade();

try {

    $usuario = $farmacia->consultarUsuario($id);

    // No permitir editar administradores.
    if (!$usuario || $usuario->getRol() !== 'Empleado') {
        header('Location: gestion_usuarios.php');
        exit;
    }

} catch (Throwable $e) {

    header('Location: gestion_usuarios.php?error=consulta');
    exit;

}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar empleado | Farmacias R-X</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>

        body {
            background: #F2F6F4;
            font-family: 'Inter', sans-serif;
            color: #243B49;
        }

        .contenedor {
            max-width: 650px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .tarjeta {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(24, 54, 74, .06);
        }

        .titulo {
            color: #18364A;
            font-weight: 700;
        }

        .btn-guardar {
            background: #299B79;
            color: white;
            border: none;
        }

        .btn-guardar:hover {
            background: #218563;
            color: white;
        }

    </style>

</head>

<body>

<div class="contenedor">

    <div class="mb-4">

        <a href="gestion_usuarios.php" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i>
            Regresar a usuarios
        </a>

        <h2 class="titulo mt-3">
            <i class="bi bi-person-gear"></i>
            Editar empleado
        </h2>

        <p class="text-secondary">
            Modifica la información de la cuenta.
        </p>

    </div>

    <div class="card tarjeta">

        <div class="card-body p-4">

            <form
                action="../../scripts/procesar_cambios_usuarios.php"
                method="POST"
            >

                <input
                    type="hidden"
                    name="id_usuario"
                    value="<?= htmlspecialchars((string)$usuario->getIdUsuario()) ?>"
                >

                <div class="mb-3">

                    <label class="form-label">Nombre completo</label>

                    <input
                        type="text"
                        name="nombre"
                        class="form-control"
                        maxlength="100"
                        value="<?= htmlspecialchars($usuario->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
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
                        value="<?= htmlspecialchars($usuario->getUsuario(), ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        minlength="12"
                        placeholder="Dejar vacío para conservar la actual"
                    >

                    <small class="text-secondary">
                        Solo completa este campo si deseas cambiar la contraseña.
                    </small>

                </div>

                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-guardar">
                        <i class="bi bi-check-circle"></i>
                        Guardar cambios
                    </button>

                    <a href="gestion_usuarios.php" class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
