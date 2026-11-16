
<?php

// Cargar nuestro autoload
require_once 'backend/config/autoload.php';

// Importar la clase
use App\Models\Medico;

// Crear un objeto
$medico = new Medico("Juan", "Cardiología");

// Mostrar información
echo $medico->getNombre() . "<br>";
echo $medico->getEspecialidad();

?>
