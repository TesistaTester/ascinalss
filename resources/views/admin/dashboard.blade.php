{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('titulo', 'Dashboard')

@push('estilos')
<style>
    /* --- Stat cards --- */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 18px;
        border: 1px solid rgba(0,0,0,0.04);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .stat-icon.gold { background: rgba(201,161,90,0.12); color: #c9a15a; }
    .stat-icon.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .stat-icon.green { background: rgba(16,185,129,0.1); color: #10b981; }
    .stat-icon.purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }
    .stat-icon.orange { background: rgba(245,158,11,0.1); color: #f59e0b; }

    .stat-label {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #1a2332;
        line-height: 1;
    }

    /* --- Accesos rápidos --- */
    .quick-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 12px;
        text-decoration: none;
        color: #1a2332;
        font-size: 14px;
        font-weight: 500;
        transition: background .2s, border-color .2s, transform .2s;
    }
    .quick-btn:hover {
        background: #f8fafc;
        border-color: #c9a15a;
        color: #c9a15a;
        transform: translateX(3px);
    }
    .quick-btn i { font-size: 16px; color: #c9a15a; width: 20px; }
    .quick-btn .quick-arrow { margin-left: auto; color: #cbd5e1; font-size: 12px; }

    /* --- Tabla últimos comunicados --- */
    .com-tipo-badge {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .badge-normal   { background: #f1f5f9; color: #64748b; }
    .badge-destacado { background: #fef3c7; color: #92400e; }
    .badge-novedad  { background: #dbeafe; color: #1e40af; }
    .badge-modal    { background: #fee2e2; color: #991b1b; }

    .table th { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; font-weight: 600; border-bottom: 1px solid #f1f5f9; }
    .table td { font-size: 13.5px; color: #334155; vertical-align: middle; border-bottom: 1px solid #f8fafc; }
    .table tr:last-child td { border-bottom: none; }

    /* --- Sección bienvenida --- */
    .welcome-card {
        background: linear-gradient(135deg, #0d1420 0%, #1a2332 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .welcome-card::before {
        content: '\F5D3';
        font-family: 'Bootstrap Icons';
        position: absolute;
        right: -10px; top: -20px;
        font-size: 140px;
        color: rgba(255,255,255,0.04);
        line-height: 1;
    }
    .welcome-card .eyebrow {
        font-size: 11px; letter-spacing: 2px; color: #c9a15a;
        text-transform: uppercase; margin-bottom: 6px;
    }
    .welcome-card h3 { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
    .welcome-card p { font-size: 13px; color: rgba(255,255,255,.6); margin: 0; }
    .welcome-card .welcome-date {
        position: absolute; right: 32px; top: 50%; transform: translateY(-50%);
        text-align: right;
    }
    .welcome-card .welcome-date .dia {
        font-size: 48px; font-weight: 800; color: rgba(255,255,255,0.08); line-height: 1;
    }
    .welcome-card .welcome-date .fecha {
        font-size: 12px; color: rgba(255,255,255,0.4);
    }
</style>
@endpush

@section('contenido')

{{-- Bienvenida --}}
<div class="welcome-card">
    <p class="eyebrow">Panel Administrativo</p>
    <h3>Bienvenido, {{ explode(' ', auth()->user()->usu_nombre_completo)[0] }}</h3>
    <p>Gestiona el contenido del sitio público de ASCINALSS desde aquí.</p>
    <div class="welcome-date d-none d-md-block">
        <div class="dia">{{ now()->format('d') }}</div>
        <div class="fecha">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2-4">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="bi bi-megaphone"></i></div>
            <div>
                <div class="stat-label">Comunicados</div>
                <div class="stat-value">{{ $resumen['comunicados'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-building"></i></div>
            <div>
                <div class="stat-label">Servicios</div>
                <div class="stat-value">{{ $resumen['servicios'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-handshake"></i></div>
            <div>
                <div class="stat-label">Convenios</div>
                <div class="stat-value">{{ $resumen['convenios'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-geo-alt"></i></div>
            <div>
                <div class="stat-label">Filiales</div>
                <div class="stat-value">{{ $resumen['filiales'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-label">Usuarios</div>
                <div class="stat-value">{{ $resumen['usuarios'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Últimos comunicados --}}
    <div class="col-lg-8">
        <div class="panel-card h-100">
            <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-3 border-bottom">
                <h6 class="mb-0 fw-bold" style="color:#1a2332;">
                    <i class="bi bi-clock-history me-2 text-muted"></i>Últimos Comunicados
                </h6>
                <a href="{{ route('comunicados.index') }}"
                   style="font-size:12px; color:#c9a15a; text-decoration:none;">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="px-2">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Título</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosComunicados as $com)
                            <tr>
                                <td class="ps-3">
                                    <div style="font-weight:500; line-height:1.3;">{{ $com->com_titulo }}</div>
                                </td>
                                <td>
                                    <span class="com-tipo-badge badge-{{ $com->com_tipo }}">
                                        {{ ucfirst($com->com_tipo) }}
                                    </span>
                                </td>
                                <td style="color:#94a3b8; font-size:12px;">
                                    {{ $com->com_fecha_publicacion->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4" style="color:#94a3b8; font-size:13px;">
                                    <i class="bi bi-inbox me-2"></i>Sin comunicados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="col-lg-4">
        <div class="panel-card h-100 p-4">
            <h6 class="fw-bold mb-3" style="color:#1a2332;">
                <i class="bi bi-lightning-charge me-2 text-muted"></i>Accesos rápidos
            </h6>
            <div class="d-flex flex-column gap-2">
                @if(auth()->user()->esAdmin() || auth()->user()->esEditor())
                <a href="{{ route('comunicados.create') }}" class="quick-btn">
                    <i class="bi bi-plus-circle"></i> Nuevo comunicado
                    <i class="bi bi-chevron-right quick-arrow"></i>
                </a>
                @endif
                @if(auth()->user()->esAdmin() || auth()->user()->esEditor())
                <a href="{{ route('servicios.index') }}" class="quick-btn">
                    <i class="bi bi-building"></i> Gestionar servicios
                    <i class="bi bi-chevron-right quick-arrow"></i>
                </a>
                @endif
                @if(auth()->user()->esAdmin() || auth()->user()->esEditor())
                <a href="{{ route('convenios.index') }}" class="quick-btn">
                    <i class="bi bi-handshake"></i> Gestionar convenios
                    <i class="bi bi-chevron-right quick-arrow"></i>
                </a>
                @endif
                @if(auth()->user()->esAdmin() || auth()->user()->esEditor())
                <a href="{{ route('filiales.index') }}" class="quick-btn">
                    <i class="bi bi-geo-alt"></i> Ver filiales
                    <i class="bi bi-chevron-right quick-arrow"></i>
                </a>
                @endif
                @if(auth()->user()->esAdmin() || auth()->user()->esEditor())
                <a href="{{ route('informes-anuales.index') }}" class="quick-btn">
                    <i class="bi bi-file-earmark-pdf"></i> Informes anuales
                    <i class="bi bi-chevron-right quick-arrow"></i>
                </a>
                @endif
                @if(auth()->user()->esAdmin())
                    <a href="{{ route('usuarios.index') }}" class="quick-btn">
                        <i class="bi bi-people"></i> Gestionar usuarios
                        <i class="bi bi-chevron-right quick-arrow"></i>
                    </a>
                @endif
                <a href="{{ route('publico.inicio') }}" target="_blank" class="quick-btn" style="border-color:rgba(201,161,90,0.3);">
                    <i class="bi bi-globe"></i> Ver sitio público
                    <i class="bi bi-arrow-up-right quick-arrow"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection