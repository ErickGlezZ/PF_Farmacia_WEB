<?php

require_once __DIR__ . '/../../backend/config/Autoload.php';

use App\Config\Sesion;
use App\Middleware\AuthMiddleware;

// Iniciar la sesión actual
Sesion::iniciar();

// Cerrar y destruir la sesión
AuthMiddleware::cerrarSesion();

// Regresar al login
header('Location: ../pages/login.php');
exit;
