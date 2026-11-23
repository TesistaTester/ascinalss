# Simulador y presolicitudes de préstamos

La calculadora estima capacidad, cuota y plan de pagos; recibe presolicitudes compatibles para preparar la atención en oficina. No aprueba préstamos ni sustituye el sistema externo de solicitudes, la verificación documental o los contratos.

## Activación

1. Respaldar la base de datos y mantener la misma APP_KEY. Los formularios, simulaciones y revisiones se guardan cifrados con esa clave.
2. Aplicar `php artisan migrate`. La nueva migración añade `pro_reglas_simulacion` y las tablas `presolicitudes_prestamo` y `revisiones_presolicitud`. No requiere `migrate:fresh` ni volver a ejecutar todos los seeders.
3. Revisar Configuración / Simulador de Préstamos con un administrador. Las reglas adicionales toman valores reglamentarios iniciales sin sobrescribir las condiciones financieras guardadas.
4. En Usuarios, asignar a la persona encargada el rol **Evaluador de préstamos** (valor 4; nombre funcional `evaluador-prestamos`). No se crea una cuenta ni contraseña predeterminada.
5. Limpiar las cachés de rutas y vistas al desplegar: `php artisan route:clear` y `php artisan view:clear`. Regenerarlas según el procedimiento del entorno.

Los cambios del código no aplican automáticamente migraciones ni publican el sitio. Las pruebas usan SQLite aislado, sin modificar la base configurada en `.env`.

## Criterios de estimación

- Productos: Regular con Garantes, Regular sin Garantes, Emergencia e Iniciación.
- Cuando los parámetros financieros permanecen pendientes, puede habilitarse una aproximación francesa mensual sobre saldos. Tasa inicial de referencia: regulares 11,99% anual nominal; Emergencia e Iniciación 2% mensual.
- Capacidad provisional autorizada para la simulación: menor entre 75% del líquido ajustado y ese líquido menos Bs 825, limitado inferiormente a cero. Tanto el porcentaje como el residual y su activación son configurables. No se etiqueta este criterio como norma institucional confirmada.
- Las condiciones financieras y de capacidad existentes con estado confirmado o inferido tienen prioridad. Los registros históricos o no vigentes no habilitan nuevas simulaciones.
- Se aplican escalas por aportes para Emergencia y Sin Garantes, saldos acumulados, restricciones de Iniciación, continuidad y frecuencia de Emergencia, declaración de mora y límite de 420 meses de servicio.
- Casos pasivos, fuera de escala o con más de 25 años de servicio se derivan a evaluación particular. DAARO y garantes se verifican en oficina; la calculadora no consulta registros institucionales.
- En refinanciamiento se separan las cuotas ya descontadas que se liberan y el saldo para cancelar las obligaciones. La simulación no valida el saldo real ni conversiones de deudas históricas en moneda extranjera.
- Cargos de otorgación: Bs 70 para Iniciación y Regulares por defecto. El cargo de Emergencia de Bs 35 y las comisiones de retención no se incluyen automáticamente mientras no se configure su tratamiento; el resultado lo señala.
- Las fechas opcionales usan meses calendario sin desbordar fin de mes. Los cálculos monetarios se presentan a dos decimales y el último capital se ajusta para cerrar el saldo. No se intenta replicar la primera cuota irregular ni el cálculo diario del sistema de cartera.

## Flujo público

El modal tiene cuatro pasos: préstamo, condiciones, estimación y presolicitud opcional. Conserva los datos al retroceder y obliga a recalcular cuando cambian las entradas. Usa GSAP, respeta movimiento reducido y mantiene el foco del teclado dentro del diálogo.

Solo `COMPATIBLE` permite enviar. Los otros estados son `SUPERA_CAPACIDAD`, `INCUMPLE_REQUISITO` y `REQUIERE_EVALUACION`. El resultado puede incluir cuota y cronograma aunque no permita envío. Un máximo personal desconocido se muestra pendiente, nunca se reemplaza por el máximo comercial.

El servidor conserva la última simulación en la sesión durante 30 minutos y vuelve a comprobar las condiciones al enviar. Un identificador repetido no crea otra presolicitud. Las páginas públicas de impresión requieren la sesión que generó el documento; no son enlaces públicos consultables por referencia.

El usuario revisa el formulario y acepta las condiciones antes de enviar. El comprobante muestra una referencia y permite imprimir formulario y plan originales. La aceptación registra fecha, versión y texto de condiciones.

## Evaluador

Administrador (1) y Evaluador de préstamos (4) pueden consultar, corregir e imprimir presolicitudes. El evaluador no accede a tasas, configuración, usuarios, contenido ni dashboard. Las cuentas inactivas no pasan el middleware de roles.

Estados operativos: Recibida, En revisión, Observada y Lista para atención. Ninguno representa aprobación crediticia. El evaluador puede corregir condiciones y recalcular; el original permanece inmutable y cada revisión guarda usuario, fecha, datos y versión. Una edición concurrente se rechaza para impedir sobrescrituras inadvertidas.

La marca de documentación completa registra cuándo el operador verificó los requisitos sin observaciones, omisiones ni alteraciones; ese evento inicia el plazo del artículo 24. Recibir una presolicitud o imprimirla no inicia dicho plazo.

No se enviaron mensajes a terceros ni se creó integración con `registro.ascinalss.org`. La presolicitud prepara la atención presencial dentro de este CMS.

## Verificación

`php vendor/bin/phpunit tests/Unit/LoanAmortizationServiceTest.php tests/Unit/LoanCapacityServiceTest.php tests/Feature/PrestamosWorkflowTest.php --do-not-cache-result`

Las pruebas de integración crean únicamente las tablas necesarias en SQLite en memoria. Cubren cálculos, reglas, permisos, aceptación, envío duplicado, cambios de configuración, cifrado, revisión y conservación del original.
