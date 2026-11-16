<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Farmacias R-X</title>

    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <main class="contenedor-login">

        <div class="login-card">

            <div class="logo">
                <h1>Farmacias R-X</h1>
                <p>Sistema de administración</p>
            </div>

            <form action="dashboard.php" method="POST">

                <div class="campo">
                    <label for="usuario">Usuario</label>

                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        placeholder="Ingrese su usuario">
                </div>

                <div class="campo">
                    <label for="contrasena">Contraseña</label>

                    <input
                        type="password"
                        id="contrasena"
                        name="contrasena"
                        placeholder="Ingrese su contraseña">
                </div>

                <button type="submit">
                    Iniciar sesión
                </button>

            </form>

            <p class="pie-login">
                Sistema de gestión de farmacias
            </p>

        </div>

    </main>

</body>
</html>