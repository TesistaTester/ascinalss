# AGENTS.md — ASCINALSS CMS
> Resumen técnico completo del proyecto para retomar contexto en nuevas sesiones.
> Generado en agosto 2026 · Desarrollado por MentorHub Factory · Jay (desarrollador principal)

---

## 1. Descripción general

Sistema de Gestión de Contenido (CMS) para **ASCINALSS** — Asociación Nacional de Suboficiales y Sargentos de las Fuerzas Armadas de la Nación de Bolivia. Es una institución gremial que representa al personal militar en Bolivia, ofreciendo servicios financieros (préstamos), beneficios sociales y espacios institucionales para sus afiliados.

El sistema consta de dos partes:
- **Sitio público**: frontend institucional con animaciones GSAP, parallax scroll y carrusel 3D. Una sola página (index) con secciones ancladas + 3 páginas independientes (filiales, comunicados, informe anual).
- **Panel administrativo**: CRUD completo para gestión de contenido, accesible solo por usuarios autenticados con roles.

El sistema de solicitud de préstamos es externo (`registro.ascinalss.org`), solo enlazado desde el sitio — no forma parte de este proyecto.

---

## 2. Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11, PHP 8.2 |
| Base de datos | PostgreSQL (migración futura a MySQL prevista) |
| Frontend admin | Bootstrap 5.3 + Bootstrap Icons |
| Frontend público | CSS custom + GSAP 3.12.5 + ScrollTrigger |
| Fuentes/íconos | Font Awesome 6.5.1 (CDN) |
| Autenticación | Auth:: custom sin Breeze/Jetstream |
| Almacenamiento | Laravel Storage, disco `public` |
| Servidor web | Nginx + PHP-FPM |
| HTTPS | Let's Encrypt / Certbot |

> ⚠️ **Pendiente**: migrar de PostgreSQL a MySQL cuando el cliente lo decida. Las migraciones que usan `DB::statement()` para tipos ENUM tienen sintaxis específica de PostgreSQL. Revisar al cambiar de motor.

---

## 3. Convenciones del proyecto

Estas convenciones aplican a **todo** el código y deben mantenerse sin excepción:

- **Primary keys**: `increments()` (no `id()`)
- **SoftDeletes**: en todos los modelos con contenido gestionable
- **Prefijos de columna por tabla**: 3 letras específicas por tabla (ej. `usu_`, `com_`, `ser_`, `conv_`, `fil_`, `inf_`, `cat_`, `doc_`, `ini_`, `cfg_`)
- **Autenticación**: `Auth::attempt()` manual, guard `web`, provider `users` apuntando a `App\Models\Usuario`
- **Sin `remember_token`**: los tres métodos están sobrescritos con no-op en el modelo Usuario
- **Sin Breeze, Jetstream, Fortify ni Sanctum**
- **CSRF**: `VerifyCsrfToken` middleware activo, todos los forms con `@csrf`
- **Rutas**: `Route::resource()` para CRUDs del admin; rutas públicas con prefijo `web/` para evitar conflicto con las del admin
- **Roles**: enteros en columna `usu_rol` (1=Admin, 2=Editor, 3=Directorio), sin Spatie Permission
- **Middleware de roles**: `VerificarRol` custom, registrado como alias `rol` en `bootstrap/app.php`
- **Campos de contraseña**: `Hash::make()` siempre, nunca texto plano
- **Validación**: inline en controladores con `$request->validate()`, sin Form Requests separados
- **Vistas admin**: Bootstrap 5, sin Filament ni Nova
- **Vistas públicas**: CSS custom + GSAP, sin Tailwind ni Alpine

---

## 4. Estructura de base de datos

### Tablas y prefijos

| Tabla | Prefijo | Descripción |
|---|---|---|
| `usuarios` | `usu_` | Usuarios del panel admin |
| `configuracion` | `cfg_` | Key-value de datos globales (contacto, slogan, etc.) |
| `servicios` | `ser_` | Servicios institucionales (Hotel, Salón, Complejo, etc.) |
| `convenios` | `conv_` | Convenios con entidades aliadas |
| `filiales` | `fil_` | Filiales nacionales (19 ciudades) |
| `informes_anuales` | `inf_` | Informes/revistas anuales en PDF |
| `categorias_prestamo` | `cat_` | Categorías de Apoyo Económico |
| `documentos_prestamo` | `doc_` | PDFs de requisitos/contratos/formularios por categoría |
| `comunicados` | `com_` | Comunicados, novedades y avisos institucionales |
| `inicio_slides` | `ini_` | Slides del hero (tabla reservada, sin uso en frontend aún) |

### Columnas relevantes no estándar

- `comunicados.com_tipo`: ENUM `('normal', 'modal', 'destacado', 'novedad')` — los de tipo `modal` se muestran como ventanas emergentes al cargar el sitio; los de tipo `novedad` soportan `com_video_url` y `com_pptx_archivo`
- `configuracion`: patrón key-value con helper estático `Configuracion::obtener('clave')` y `Configuracion::establecer('clave', 'valor')`
- `filiales.fil_imagen`: columna agregada vía migración adicional (no estaba en la migración original)

---

## 5. Modelos y relaciones

```
Usuario          → HasMany: Comunicado (com_usuario_id → usu_id)
CategoriaPrestamo → HasMany: DocumentoPrestamo (doc_categoria_id → cat_id)
DocumentoPrestamo → BelongsTo: CategoriaPrestamo
Comunicado        → BelongsTo: Usuario
```

### Constantes de modelo

```php
// Usuario
ROL_ADMIN = 1 | ROL_EDITOR = 2 | ROL_DIRECTORIO = 3

// Comunicado
TIPO_NORMAL = 'normal' | TIPO_MODAL = 'modal'
TIPO_DESTACADO = 'destacado' | TIPO_NOVEDAD = 'novedad'

// DocumentoPrestamo
TIPO_REQUISITOS = 'requisitos' | TIPO_CONTRATO = 'contrato' | TIPO_FORMULARIO = 'formulario'
```

### Helpers de modelo

```php
Usuario::esAdmin()      // usu_rol === 1
Usuario::esEditor()     // usu_rol === 2
Usuario::esDirectorio() // usu_rol === 3
Comunicado::esModal()   // com_tipo === 'modal'
Comunicado::esNovedad() // com_tipo === 'novedad'
Comunicado::vigente()   // com_fecha_expiracion es null o futura
Configuracion::obtener(string $clave, $default = null)
Configuracion::establecer(string $clave, $valor)
```

---

## 6. Controladores

### Panel administrativo (`/admin/*` con middleware `auth` + `rol`)

| Controlador | Ruta base | Acceso |
|---|---|---|
| `AuthController` | `/acceso`, `/auth`, `/logout` | Público |
| `DashboardController` | `/dashboard` | Admin + Editor + Directorio |
| `ComunicadoController` | `/comunicados` | Admin + Editor |
| `ServicioController` | `/servicios` | Admin + Editor |
| `ConvenioController` | `/convenios` | Admin + Editor |
| `FilialController` | `/filiales` | Admin + Editor |
| `InformeAnualController` | `/informes-anuales` | Admin + Editor |
| `CategoriaPrestamoController` | `/categorias-prestamo` | Admin + Editor |
| `DocumentoPrestamoController` | `/categorias-prestamo/{id}/documentos` | Admin + Editor |
| `UsuarioController` | `/usuarios` | Solo Admin |

### Sitio público

| Controlador | Rutas |
|---|---|
| `PublicoController` | `GET /` (index), `GET /web/filiales`, `GET /web/comunicados`, `GET /web/informe-anual` |

### Rutas con parámetro personalizado

```php
Route::resource('/informes-anuales', InformeAnualController::class)
    ->parameters(['informes-anuales' => 'informe']);
Route::resource('/categorias-prestamo', CategoriaPrestamoController::class)
    ->parameters(['categorias-prestamo' => 'categoriasPrestamo']);
```

---

## 7. Vistas

### Layout público (`layouts/publico.blade.php`)
- Carga Font Awesome, GSAP + ScrollTrigger desde CDN
- Inyecta `@include('publico._estilos')` en `<style>` del `<head>`
- Inyecta `@include('publico._script')` al final del `<body>` (después de GSAP)

### Parciales públicos clave
- `publico/_nav.blade.php` — navegación compartida entre todas las páginas públicas
- `publico/_estilos.blade.php` — todo el CSS del sitio público
- `publico/_script.blade.php` — todo el JavaScript (GSAP, carrusel, modales, mapa, menú móvil)

> ⚠️ **Regla crítica de JS**: cualquier `getElementById()` o `querySelector()` que dependa de un elemento que no existe en todas las páginas **debe** estar protegido con `if (elemento) { ... }`. Sin esta protección, el script se rompe en cadena y ninguna animación funciona en esa página. Errores ocurridos por este patrón: `#particles`, `#burger`, `#modalServicio`, `#cerrarModalServicio`, `#modalPrestamo`.

### Páginas públicas
- `publico/index.blade.php` — home con secciones: Hero, Servicios, Apoyo Económico, Convenios, CTA (mapa), Contacto
- `publico/filiales.blade.php` — hero con mapa SVG animado de fondo + grid de filiales
- `publico/comunicados.blade.php` — hero con parallax + grid de comunicados + modal de detalle
- `publico/informe-anual.blade.php` — hero con parallax + lista de informes tipo directorio
- `publico/login.blade.php` — login glassmorphism con imagen hero de fondo y partículas GSAP

### Layout admin (`layouts/admin.blade.php`)
- Bootstrap 5.3 + Bootstrap Icons
- Sidebar fijo 260px, fondo `#0d1420` (navy)
- Links activos en dorado `#c9a15a` con fondo semitransparente
- Muestra nombre y rol del usuario en footer del sidebar
- Breadcrumb via `@yield('breadcrumb')` en cada vista
- Soporta `@push('estilos')` y `@stack('scripts')` por página

---

## 8. Animaciones y efectos (GSAP)

### Parallax de imágenes de fondo
Patrón estándar aplicado en Hero, Apoyo Económico, Convenios, Comunicados, Filiales e Informe Anual:
```css
.seccion-bg { height: 150%; top: 0; left: 0; width: 100%; position: absolute; }
```
```js
gsap.to(elemento, {
    yPercent: -35,
    ease: 'none',
    scrollTrigger: { trigger: '#seccion', start: 'top top', end: 'bottom top', scrub: true }
});
```

### Split de letras (animación letra por letra)
Función `splitLetters(el)` en `_script.blade.php` — divide el texto en `<span class="split-char">` sin plugins externos. Aplicada a todos los elementos `.split-title`.

### Carrusel 3D de Convenios
Motor custom en `_script.blade.php` con `calcularParametros()` responsivo (desktop: espaciado 260, profundidad 220, rotación 32° / móvil: 230, 200, 30°). Solo 1 tarjeta visible a cada lado (prev/activo/next). Autoplay cada 2s, se pausa con `mouseenter`, se reanuda con `mouseleave`. El carrusel **debe llamar a `siguiente()` una vez antes de iniciar el `setInterval`**, de lo contrario permanece estático los primeros 2 segundos.

### Mapa SVG de Bolivia
El SVG (`public/img/mapa-bolivia.svg`) se carga via `fetch()` e inyecta inline para poder manipular sus `<path>` con GSAP. Configuración requerida:
```js
svgEl.setAttribute('viewBox', '0 0 2000 2208');
svgEl.removeAttribute('width');
svgEl.removeAttribute('height');
```
9 departamentos (paths), sin `viewBox` en el archivo original. Los textos/captions se eliminan con `.querySelectorAll('text').forEach(t => t.remove())`. El fill inline de los paths se sobreescribe con `path.style.fill` (no con CSS externo, ya que tiene mayor especificidad). Ciclo automático de 800ms con `setInterval`.

### Modales
Sistema unificado de modales con clases CSS base `.modal-overlay` + `.modal-box`. Tipos:
- `.modal-comunicado` — auto-apertura en cascada al cargar (si hay comunicados tipo `modal` vigentes)
- `.modal-servicio-overlay` — se abre al hacer click en card de Servicio (datos vía `data-*`)
- `#modalPrestamo` — se abre al hacer click en categoría de préstamo (documentos generados dinámicamente)
- `#modalComunicado` — detalle de comunicado en página `/web/comunicados`

---

## 9. Comando de importación de archivos

```bash
php artisan ascinalss:importar-archivos          # importa y omite existentes
php artisan ascinalss:importar-archivos --forzar # sobreescribe todo
```

Descarga desde `http://www.ascinalss.org/ascinalss/` y guarda en `storage/app/public/`:
- `servicios/` — 5 imágenes de servicios
- `convenios/logos/` y `convenios/pdfs/` — logos y PDFs de convenios
- `informes-anuales/` — PDF de la revista y portada
- `prestamos/documentos/` — todos los PDFs de requisitos/contratos/formularios
- `comunicados/` — PDFs e imágenes de comunicados
- `filiales/` — 18 fotografías de filiales (Riberalta sin foto disponible aún)

Después de cada `migrate:fresh --seed`, ejecutar este comando para reconectar los archivos existentes con los nuevos registros de BD (sin re-descargar).

---

## 10. Seeders (datos reales de ASCINALSS)

| Seeder | Datos insertados |
|---|---|
| `UsuarioSeeder` | 3 usuarios: admin, editor, directorio (contraseña temporal: `CambiarEstaClave2026*`) |
| `ConfiguracionSeeder` | Dirección, teléfonos por área, WhatsApp, Facebook, slogan del hero |
| `ServicioSeeder` | 5 servicios: Salón Dorado, Hotel Casa Comunitaria, Complejo Cota Cota, Multifamiliar Juancito Pinto, Salón de Banderas |
| `ConvenioSeeder` | 5 convenios: Nacional Seguros, EMI, ATENEA, UNITEPC, Unidad Educativa América |
| `FilialSeeder` | 19 filiales: La Paz (Central) + 18 ciudades del país |
| `InformeAnualSeeder` | 1 informe: Revista Gestión 2023-2025 |
| `CategoriaPrestamoSeeder` | 6 categorías con sus documentos anidados: Emergencia, Regulares c/s Garantes, Iniciación, D.A.A.R.O., Afiliaciones |
| `ComunicadoSeeder` | 5 comunicados históricos de ejemplo |

Orden de ejecución en `DatabaseSeeder`: usuarios → configuracion → servicios → convenios → filiales → informes → categorias (con documentos) → comunicados.

---

## 11. Configuraciones de contacto en BD

Claves en la tabla `configuracion`:
```
direccion_principal | telefono_central | telefono_prestamos
telefono_cobranzas | telefono_daaro | telefono_tesoreria
whatsapp_solicitudes | facebook_url | nombre_institucion
sigla_institucion | hero_slogan
```

---

## 12. Archivos de imagen/media requeridos en `public/img/`

| Archivo | Uso |
|---|---|
| `logo.png` | Logo grande en el hero del sitio público |
| `logo-top-menu.png` | Logo pequeño en el nav (36px de alto) |
| `hero-ascinalss.png` | Imagen de fondo del hero público y del login admin |
| `mapa-bolivia.svg` | Mapa SVG de Bolivia con departamentos |
| `prestamos.jpg` | Fondo parallax sección Apoyo Económico |
| `convenios.avif` | Fondo parallax sección Convenios |
| `comunicados.png` | Fondo parallax hero página /comunicados |
| `informes.avif` | Fondo parallax hero página /informe-anual |
| `filiales.avif` | Fondo parallax hero página /filiales |
| `ejercito.png` | Logo Ejército de Bolivia (logos institucionales del hero) |
| `fab.png` | Logo Fuerza Aérea Boliviana |
| `armada.png` | Logo Armada Boliviana |

---

## 13. Manuales generados

| Documento | Descripción |
|---|---|
| `manual-instalacion-ascinalss.docx` | Manual completo (15 capítulos, ~13 páginas, redacción formal) |
| `guia-rapida-instalacion-ascinalss.docx` | Cheat sheet en formato landscape 2 columnas |

---

## 14. Pendientes conocidos

- [ ] Conseguir fotografía de **Filial Riberalta** para completar las 19 filiales con imagen
- [ ] Migrar base de datos de **PostgreSQL a MySQL** cuando el cliente lo decida — revisar todas las migraciones que usan `DB::statement()` con sintaxis PostgreSQL (especialmente la de `comunicados_com_tipo_check`)
- [ ] Páginas independientes de **Comunicados e Informe Anual** pendientes de aplicar el hero con imagen parallax en producción final
- [ ] Revisar paginación de comunicados — Laravel genera clases Tailwind por defecto; ejecutar `php artisan vendor:publish --tag=laravel-pagination` y personalizar la vista para que use el CSS oscuro del sitio

---

## 15. Flujo completo de instalación (resumen de comandos)

```bash
# 1. Clonar y preparar
cd /var/www && git clone https://repo/ascinalss-cms.git
cd ascinalss-cms
composer install --optimize-autoloader --no-dev
cp .env.example .env && nano .env   # configurar variables
php artisan key:generate

# 2. Permisos
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache

# 3. Base de datos (PostgreSQL)
sudo -u postgres psql
# CREATE USER ascinalss_user WITH PASSWORD '...';
# CREATE DATABASE ascinalss_db OWNER ascinalss_user;
# GRANT ALL PRIVILEGES ON DATABASE ascinalss_db TO ascinalss_user; \q

# 4. Migraciones y datos
php artisan migrate
php artisan db:seed

# 5. Storage y multimedia
php artisan storage:link
php artisan ascinalss:importar-archivos

# 6. Optimización
php artisan config:cache && php artisan route:cache
php artisan view:cache && composer dump-autoload --optimize

# 7. Nginx + HTTPS
sudo nginx -t && sudo systemctl reload nginx
sudo certbot --nginx -d ascinalss.org -d www.ascinalss.org
```

---

*AGENTS.md generado en agosto 2026 · MentorHub Factory · Jay*
