/* Driver.js is loaded only when a tour is requested. */
(() => {
    'use strict';

    const baseUrl = 'https://cdn.jsdelivr.net/npm/driver.js@1.8.0/dist/';
    let loading;

    function loadDriver() {
        if (window.driver?.js?.driver) return Promise.resolve(window.driver.js.driver);
        if (loading) return loading;

        loading = new Promise((resolve, reject) => {
            const style = document.createElement('link');
            style.rel = 'stylesheet';
            style.href = baseUrl + 'driver.css';
            const script = document.createElement('script');
            script.src = baseUrl + 'driver.js.iife.js';
            script.async = true;
            let loaded = 0;
            const ready = () => {
                if (++loaded === 2) {
                    if (window.driver?.js?.driver) resolve(window.driver.js.driver);
                    else failed();
                }
            };
            const failed = () => {
                style.remove();
                script.remove();
                loading = undefined;
                reject(new Error('No se pudo cargar el tutorial. Intenta nuevamente.'));
            };
            style.onload = ready;
            script.onload = ready;
            style.onerror = failed;
            script.onerror = failed;
            document.head.append(style, script);
        });
        return loading;
    }

    window.AscinalssTours = Object.freeze({
        async crear(options = {}) {
            const driver = await loadDriver();
            return driver({
                showProgress: true,
                nextBtnText: 'Siguiente',
                prevBtnText: 'Anterior',
                doneBtnText: 'Finalizar',
                progressText: '{{current}} de {{total}}',
                allowClose: true,
                disableActiveInteraction: false, // Permite escribir/interactuar con el input en foco
                ...options,
                animate: !window.matchMedia('(prefers-reduced-motion: reduce)').matches
                    && options.animate !== false,
            });
        },
    });
})();
