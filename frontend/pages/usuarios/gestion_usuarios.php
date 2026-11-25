<?php

require_once __DIR__ . '../../../../backend/config/Autoload.php';

use App\Middleware\AuthMiddleware;
use App\facade\FarmaciaFacade;

// Solamente el administrador puede entrar.
AuthMiddleware::requireRole(
    ['Administrador'],
    '../login.php'
);

// Obtener usuarios.
$farmacia = new FarmaciaFacade();

try {

    $usuarios = $farmacia->listarUsuarios();

} catch (Throwable $e) {

    $error = 'No fue posible consultar los usuarios.';

}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestión de usuarios | Farmacias R-X</title>

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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .titulo {
            color: #18364A;
            font-weight: 700;
        }

        .tarjeta {
            background: white;
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(24, 54, 74, .06);
        }

        .tabla thead th {
            background: #F2F6F4;
            color: #72828B;
            font-size: 13px;
            padding: 15px;
            white-space: nowrap;
        }

        .tabla tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .estado {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .activo {
            background: #E5F1EC;
            color: #299B79;
        }

        .inactivo {
            background: #FCE8E8;
            color: #C0392B;
        }

        .btn-registro {
            background: #299B79;
            color: white;
            border: none;
        }

        .btn-registro:hover {
            background: #218563;
            color: white;
        }

    </style>

</head>

<body>

<div class="contenedor">

    <div class="encabezado">

        <div>

            <h2 class="titulo">
                <i class="bi bi-people"></i>
                Gestión de usuarios
            </h2>

            <p class="text-secondary mb-0">
                Administra las cuentas que tienen acceso al sistema.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="../dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

            <a href="registrar_usuario.php" class="btn btn-registro">
                <i class="bi bi-person-plus"></i>
                Registrar empleado
            </a>

        </div>

    </div>

    <div class="card tarjeta">

        <div class="card-body p-0">

            <?php if (isset($error)): ?>

                <div class="alert alert-danger m-3">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php elseif (empty($usuarios)): ?>

                <div class="text-center p-5">

                    <i class="bi bi-people fs-1 text-secondary"></i>

                    <h5 class="mt-3">No hay usuarios registrados</h5>

                    <p class="text-secondary">
                        Registra un empleado para comenzar.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table tabla table-hover mb-0">

                        <thead>

                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha de creación</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($usuarios as $usuario): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars((string)$usuario->getIdUsuario()) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($usuario->getNombre(), ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($usuario->getUsuario(), ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>

                                        <?php if ($usuario->getRol() === 'Administrador'): ?>

                                            <span class="badge text-bg-primary">
                                                Administrador
                                            </span>

                                        <?php else: ?>

                                            <span class="badge text-bg-secondary">
                                                Empleado
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if ($usuario->getEstado() == 1): ?>

                                            <span class="estado activo">
                                                Activo
                                            </span>

                                        <?php else: ?>

                                            <span class="estado inactivo">
                                                Inactivo
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $usuario->getFechaCreacion() ?? 'Sin fecha',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>

                                        <?php if ($usuario->getRol() === 'Empleado'): ?>

                                            <a
                                                href="editar_usuario.php?id=<?= urlencode((string)$usuario->getIdUsuario()) ?>"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar empleado"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form
                                                action="../../scripts/procesar_estado_usuarios.php"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Deseas cambiar el estado de esta cuenta?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id_usuario"
                                                    value="<?= htmlspecialchars((string)$usuario->getIdUsuario()) ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="estado"
                                                    value="<?= $usuario->getEstado() == 1 ? 0 : 1 ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-<?= $usuario->getEstado() == 1 ? 'danger' : 'success' ?>"
                                                    title="<?= $usuario->getEstado() == 1 ? 'Desactivar' : 'Activar' ?>"
                                                >

                                                    <i class="bi bi-<?= $usuario->getEstado() == 1 ? 'person-x' : 'person-check' ?>"></i>

                                                </button>

                                            </form>

                                        <?php else: ?>

                                            <span class="text-secondary small">
                                                Cuenta administrativa
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>
