
<?php
//Esto nos permitirá mantener organizadas las clases sin escribir require_once para cada una.
spl_autoload_register(function ($clase) {

    // Identificar el namespace principal
    $prefijo = 'App\\';

    // Comprobar que la clase pertenezca a nuestra aplicación
    if (strpos($clase, $prefijo) !== 0) {
        return;
    }

    // Eliminar el prefijo App\
    $clase = substr($clase, strlen($prefijo));

    // Convertir namespaces en rutas
    $ruta = str_replace('\\', DIRECTORY_SEPARATOR, $clase);

    // Construir la ruta del archivo
    $archivo = dirname(__DIR__) . DIRECTORY_SEPARATOR .
               $ruta . '.php';

    // Comprobar si existe el archivo
    if (file_exists($archivo)) {
        require_once $archivo;
    }

});
