# Tours guiados

Driver.js 1.8.0 está disponible en los layouts público y administrativo mediante `public/js/tours.js`. La biblioteca y sus estilos se cargan desde jsDelivr al solicitar el primer tour; no se inicia ningún recorrido automáticamente. La versión está fijada para evitar cambios inesperados. Se requiere acceso al CDN.

La función asíncrona `AscinalssTours.crear(opciones)` devuelve una instancia de Driver.js con botones en español, progreso y respeto a la preferencia de movimiento reducido. Acepta las opciones y eventos nativos de Driver.js.

Para incorporar un recorrido, agregar un botón explícito y definir sus pasos en el script de la página, por ejemplo dentro de `@push('scripts')`:

```javascript
const boton = document.querySelector('[data-iniciar-tour]');
if (boton) {
    boton.addEventListener('click', async () => {
        boton.disabled = true;
        try {
            const tour = await AscinalssTours.crear({
                steps: [
                    {
                        element: '[data-ayuda-resumen]',
                        popover: {
                            title: 'Resumen',
                            description: 'Aquí puedes revisar la información.',
                        },
                    },
                ],
            });
            tour.drive();
        } catch (error) {
            alert(error.message);
        } finally {
            boton.disabled = false;
        }
    });
}
```

Los selectores son ilustrativos; todavía no hay recorridos definidos. Usar elementos presentes y visibles en cada página, abrir los modales antes de señalar su contenido y coordinar sus controles de foco con el tour. Los textos de los pasos deben ser contenido confiable definido por el desarrollador, nunca HTML recibido de visitantes.

Documentación oficial: https://driverjs.com/docs/installation y https://driverjs.com/docs/configuration.
