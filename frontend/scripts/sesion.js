(() => {

    // Tiempo de inactividad en milisegundos (30 minutos).
    const TIEMPO_INACTIVIDAD = 30 * 60 * 1000;

    // Ruta hacia el login.
    const RUTA_LOGIN = '../pages/login.php';

    let temporizador;

    function cerrarSesionPorInactividad() {

        // Evitar que el usuario vuelva a utilizar la página.
        window.location.replace(
            RUTA_LOGIN + '?sesion=expirada'
        );
    }

    function reiniciarTemporizador() {

        clearTimeout(temporizador);

        temporizador = setTimeout(
            cerrarSesionPorInactividad,
            TIEMPO_INACTIVIDAD
        );
    }

    // Actividades que reinician el tiempo.
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

    // Iniciar el contador.
    reiniciarTemporizador();

})();
