# Archivo y Registro del Módulo de Presolicitudes de Préstamos
> **Fecha de archivo:** Noviembre 2026  
> **Proyecto:** ASCINALSS CMS  
> **Motivo:** Simplificación del flujo de usuario y desacoplamiento. El sitio web oficial mantendrá exclusivamente la calculadora/simulador referencial informativa de préstamos (con desglose visual de cuotas, montos y descarga de plan de pagos en PDF). No se realizará captura de datos personales ni recepción digital de presolicitudes en el portal web, manteniéndose el sistema externo `registro.ascinalss.org` y los canales de oficina institucional para la tramitación formal de solicitudes.

---

## 1. Resumen de Componentes Archivados

Este documento resguarda el diseño, estructura de base de datos, modelos, controladores y vistas que integraban el módulo de presolicitudes, de manera que puedan ser reactivados o consultados en el futuro sin pérdida de trabajo previo.

### 1.1. Controladores
- **`app/Http/Controllers/PresolicitudPrestamoController.php`**:
  - `store(Request $request)`: Validación de datos del solicitante (y garantes para `REG_GAR`), verificación de simulación válida en sesión (`prestamo_actual`), generación de código de referencia alfanumérico (ej. `PRE-202611-XXXX`), registro en tabla `presolicitudes_prestamo`.
  - `index(Request $request)`: Listado con filtros de estado (NUEVA, EN_REVISION, OBSERVADA, PROCEDENTE, NO_PROCEDENTE) y búsqueda por cédula militar o referencia en el panel administrativo.
  - `show(PresolicitudPrestamo $presolicitud)`: Vista detallada de la presolicitud con historial de revisiones, datos del solicitante y garantes, y formulario de recalificación.
  - `update(Request $request, PresolicitudPrestamo $presolicitud)`: Registro de revisiones por parte del evaluador/admin en `revisiones_presolicitud`.
  - `formulario(Request $request, string $simulacion)`: Vista previa e impresión del formulario prellenado antes del envío.
  - `imprimir(PresolicitudPrestamo $presolicitud)`: Impresión oficial de presolicitud y plan de pagos desde el panel administrativo.

### 1.2. Modelos y Relaciones
- **`App\Models\PresolicitudPrestamo`**:
  - Tabla: `presolicitudes_prestamo` (prefijo `pre_`)
  - Primary Key: `pre_id`
  - Traits: `SoftDeletes`
  - Campos cifrados (`encrypted`): Cédula militar, carnet de identidad, teléfonos, domicilio, correo, datos de garantes.
  - Relación: `hasMany(RevisionPresolicitud::class, 'rev_presolicitud_id', 'pre_id')`.
  - Estados: `NUEVA`, `EN_REVISION`, `OBSERVADA`, `PROCEDENTE`, `NO_PROCEDENTE`.
- **`App\Models\RevisionPresolicitud`**:
  - Tabla: `revisiones_presolicitud` (prefijo `rev_`)
  - Primary Key: `rev_id`
  - Relación: `belongsTo(PresolicitudPrestamo::class, 'rev_presolicitud_id', 'pre_id')`, `belongsTo(Usuario::class, 'rev_usuario_id', 'usu_id')`.

### 1.3. Migraciones
- `database/migrations/2026_09_02_100000_create_presolicitudes_prestamo_table.php`
- `database/migrations/2026_09_02_100001_create_revisiones_presolicitud_table.php`

### 1.4. Vistas
- `resources/views/admin/presolicitudes/index.blade.php`: Bandeja de presolicitudes en el panel admin con filtros por estado.
- `resources/views/admin/presolicitudes/show.blade.php`: Detalle de presolicitud, visualización de datos cifrados descifrados en sesión, histórico de notas y cambio de estado.
- `resources/views/prestamos/_formulario.blade.php`: Campos HTML del formulario de presolicitud (solicitante, contacto, garantes condicionales para préstamos con garantes).
- `resources/views/prestamos/imprimir.blade.php`: Plantilla de impresión que soporta tanto plan de amortización solo (`$formulario = null`) como formulario institucional completo.

---

## 2. Rutas que Operaban el Módulo

Las siguientes rutas fueron retiradas de `routes/web.php` para no exponer endpoints innecesarios:

```php
// Rutas públicas (Frontend)
Route::post('/web/prestamos/presolicitud', [PresolicitudPrestamoController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('prestamos.enviar');

Route::get('/web/prestamos/presolicitud/{simulacion}/imprimir', [PresolicitudPrestamoController::class, 'formulario'])
    ->whereUuid('simulacion')
    ->name('prestamos.formulario');

// Rutas administrativas (Panel)
Route::middleware('rol:1,4')->group(function () {
    Route::resource('/presolicitudes', PresolicitudPrestamoController::class)
        ->only(['index', 'show', 'update'])
        ->parameters(['presolicitudes' => 'presolicitud']);
        
    Route::get('/presolicitudes/{presolicitud}/imprimir', [PresolicitudPrestamoController::class, 'imprimir'])
        ->name('presolicitudes.imprimir');
});
```

---

## 3. Rol de Evaluador de Préstamos (Rol 4)

- Se había creado la constante y helper en `Usuario`:
  - `ROL_EVALUADOR_PRESTAMOS = 4`
  - `Usuario::esEvaluadorPrestamos()`: evalúa si `usu_rol === 4`.
- En caso de necesitarse nuevamente, un usuario con `usu_rol = 4` tenía acceso exclusivo al menú de presolicitudes sin acceso a la gestión de contenidos (`servicios`, `convenios`, etc.) ni de administración de usuarios.

---

## 4. Pasos para Reactivar el Módulo en el Futuro

Si ASCINALSS decide en una fase posterior habilitar la recepción web de presolicitudes:
1. **Restaurar rutas en `routes/web.php`**: descomentar o reincorporar las rutas públicas y administrativas del punto 2.
2. **Reincorporar enlace en `resources/views/layouts/admin.blade.php`**:
   ```blade
   @if(auth()->user()->esAdmin() || auth()->user()->esEvaluadorPrestamos())
   <li>
       <a href="{{ route('presolicitudes.index') }}" class="nav-link {{ request()->routeIs('presolicitudes.*') ? 'active' : '' }}">
           <i class="bi bi-folder-check"></i> Presolicitudes de préstamos
       </a>
   </li>
   @endif
   ```
3. **Restablecer el paso 4 en el modal de la calculadora (`resources/views/publico/_simulador.blade.php`)**:
   - Agregar el paso 4 en la lista `.sim-pasos`: `<li>4. Presolicitud opcional</li>`
   - Reincorporar la sección `<section data-paso="3">` que invoca `@include('prestamos._formulario')`.
4. **Actualizar navegación en `public/js/simulador-prestamos.js`**:
   - Reenlazar el botón de avance desde la estimación hacia el paso 3.
