(() => {

    // Tiempo de inactividad: 5 minutos.
    const TIEMPO_INACTIVIDAD = 5 * 60 * 1000;

    // Ruta hacia el login.
    const RUTA_LOGIN = '../pages/login.php';

    // Ruta para renovar el JWT.
    const RUTA_RENOVAR = '../scripts/renovar_token.php';

    let temporizador;
    let ultimaActividad = Date.now();
    let cerrandoSesion = false;
    let renovando = false;

    // Redirigir al login.
    function cerrarSesionPorInactividad() {

        if (cerrandoSesion) return;

        cerrandoSesion = true;

        clearTimeout(temporizador);

        window.location.replace(
            RUTA_LOGIN + '?sesion=expirada'
        );
    }

    // Renovar el JWT.
    async function renovarToken() {

        if (renovando || cerrandoSesion) return;

        renovando = true;

        try {

            const respuesta = await fetch(RUTA_RENOVAR, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!respuesta.ok) {
                cerrarSesionPorInactividad();
                return;
            }

        } catch (error) {

            console.error('Error al renovar JWT:', error);

        } finally {

            renovando = false;
        }
    }

    // Reiniciar el contador de inactividad.
    function reiniciarTemporizador() {

        if (cerrandoSesion) return;

        const ahora = Date.now();

        // Comprobar si ya transcurrieron los 5 minutos.
        if (ahora - ultimaActividad >= TIEMPO_INACTIVIDAD) {
            cerrarSesionPorInactividad();
            return;
        }

        ultimaActividad = ahora;

        clearTimeout(temporizador);

        temporizador = setTimeout(
            cerrarSesionPorInactividad,
            TIEMPO_INACTIVIDAD
        );

        renovarToken();
    }

    // Actividades que reinician el contador.
    const eventos = [
        'mousemove',
        'mousedown',
        'keydown',
        'scroll',
        'touchstart',
        'click'
    ];

    eventos.forEach(evento => {

        document.addEventListener(
            evento,
            reiniciarTemporizador,
            { passive: true }
        );

    });

    // Iniciar el contador al cargar la página.
    temporizador = setTimeout(
        cerrarSesionPorInactividad,
        TIEMPO_INACTIVIDAD
    );

})();
