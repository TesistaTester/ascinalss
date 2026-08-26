{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Panel Administrativo') - ASCINALSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #0d1420;
            --sidebar-hover: #1a2332;
            --sidebar-active: #1a2332;
            --sidebar-border: rgba(255,255,255,0.07);
            --gold: #c9a15a;
            --gold-light: #e0c088;
            --main-bg: #f0f2f7;
            --card-radius: 14px;
        }

        * { box-sizing: border-box; }
        body { background: var(--main-bg); font-family: 'Segoe UI', system-ui, sans-serif; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sidebar-brand img { width: 100%; max-height: 52px; object-fit: contain; }

        .sidebar-section-label {
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            padding: 18px 20px 6px;
            display: block;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 9px 20px;
            border-radius: 10px;
            margin: 1px 10px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .2s ease, color .2s ease;
        }
        .sidebar .nav-link i { font-size: 15px; flex-shrink: 0; }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.07);
        }
        .sidebar .nav-link.active {
            color: var(--gold);
            background: rgba(201,161,90,0.12);
            border: 1px solid rgba(201,161,90,0.2);
        }
        .sidebar .nav-link.active i { color: var(--gold); }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
            margin-top: auto;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .sidebar-user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(201,161,90,0.15);
            border: 1px solid rgba(201,161,90,0.3);
            display: flex; align-items: center; justify-content: center;
            color: var(--gold);
            font-size: 14px;
            flex-shrink: 0;
        }
        .sidebar-user-nombre {
            font-size: 12.5px;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-rol {
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            letter-spacing: .5px;
        }
        .sidebar-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 9px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 13px;
            transition: background .2s, color .2s;
        }
        .sidebar-logout:hover {
            background: rgba(255,80,80,0.1);
            border-color: rgba(255,80,80,0.25);
            color: #ff9a9a;
        }

        /* --- MAIN --- */
        main {
            flex: 1;
            padding: 28px 32px;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .page-header {
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header h4 {
            font-size: 20px;
            font-weight: 700;
            color: #1a2332;
            margin: 0;
        }
        .page-header .breadcrumb {
            font-size: 12px;
            margin: 0;
        }

        /* Alertas redondeadas */
        .alert { border-radius: var(--card-radius); border: none; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger { background: #fee2e2; color: #991b1b; }

        /* Cards del panel */
        .panel-card {
            background: #fff;
            border-radius: var(--card-radius);
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            main { padding: 20px 16px; }
        }
    </style>
    @stack('estilos')
</head>
<body>
<div class="d-flex">

    <nav class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo-top-menu.png') }}" alt="ASCINALSS">
        </div>

        <ul class="nav flex-column mb-auto pt-1" style="flex:1;">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            @if(auth()->user()->esAdmin() || auth()->user()->esEditor())
                <span class="sidebar-section-label">Contenido</span>
                <li>
                    <a href="{{ route('comunicados.index') }}"
                       class="nav-link {{ request()->routeIs('comunicados.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i> Comunicados
                    </a>
                </li>
                <li>
                    <a href="{{ route('servicios.index') }}"
                       class="nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Servicios
                    </a>
                </li>
                <li>
                    <a href="{{ route('convenios.index') }}"
                       class="nav-link {{ request()->routeIs('convenios.*') ? 'active' : '' }}">
                        <i class="bi bi-handshake"></i> Convenios
                    </a>
                </li>
                <li>
                    <a href="{{ route('filiales.index') }}"
                       class="nav-link {{ request()->routeIs('filiales.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i> Filiales
                    </a>
                </li>
                <li>
                    <a href="{{ route('informes-anuales.index') }}"
                       class="nav-link {{ request()->routeIs('informes-anuales.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf"></i> Informes Anuales
                    </a>
                </li>
                <li>
                    <a href="{{ route('categorias-prestamo.index') }}"
                       class="nav-link {{ request()->routeIs('categorias-prestamo.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-coin"></i> Apoyo Económico
                    </a>
                </li>
            @endif

            @if(auth()->user()->esAdmin())
                <span class="sidebar-section-label">Administración</span>
                <li>
                    <a href="{{ route('simulador-prestamos.index') }}"
                       class="nav-link {{ request()->routeIs('simulador-prestamos.*') ? 'active' : '' }}">
                        <i class="bi bi-calculator"></i> Simulador de Préstamos
                    </a>
                </li>
                <li>
                    <a href="{{ route('usuarios.index') }}"
                       class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <i class="bi bi-person"></i>
                </div>
                <div style="overflow:hidden;">
                    <div class="sidebar-user-nombre">{{ auth()->user()->usu_nombre_completo }}</div>
                    <div class="sidebar-user-rol">
                        @php
                            $roles = [1 => 'Administrador', 2 => 'Editor', 3 => 'Directorio'];
                        @endphp
                        {{ $roles[auth()->user()->usu_rol] ?? 'Usuario' }}
                    </div>
                </div>
            </div>
            <a href="{{ url('/logout') }}" class="sidebar-logout">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </nav>

    <main>
        <div class="page-header">
            <h4>@yield('titulo', 'Panel Administrativo')</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" style="color:#c9a15a; text-decoration:none;">Inicio</a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        @if(session('exito'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('exito') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <strong><i class="bi bi-exclamation-circle me-1"></i> Revisa lo siguiente:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('contenido')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
