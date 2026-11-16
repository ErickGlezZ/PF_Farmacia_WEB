<?php

spl_autoload_register(function ($clase) {

    $prefijo = 'App\\';

    // Verificar que la clase pertenezca a nuestro proyecto
    if (strpos($clase, $prefijo) !== 0) {
        return;
    }

    // Quitar el prefijo App\
    $clase = substr($clase, strlen($prefijo));

    // Separar el namespace de la clase
    $partes = explode('\\', $clase);

    // Convertir el nombre de la carpeta a minúsculas
    $carpeta = strtolower(array_shift($partes));

    // Obtener el nombre de la clase
    $nombreArchivo = array_pop($partes);

    // Construir la ruta
    $subruta = '';

    if (!empty($partes)) {
        $subruta = implode(DIRECTORY_SEPARATOR, $partes)
                 . DIRECTORY_SEPARATOR;
    }

    $archivo = dirname(__DIR__) . DIRECTORY_SEPARATOR .
               $carpeta . DIRECTORY_SEPARATOR .
               $subruta . $nombreArchivo . '.php';

    // Cargar el archivo si existe
    if (file_exists($archivo)) {
        require_once $archivo;
    }

});