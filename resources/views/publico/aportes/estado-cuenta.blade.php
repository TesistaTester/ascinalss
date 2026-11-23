<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Cuenta de Aportes - ASCINALSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0a101d;
            color: #e2e8f0;
            min-height: 100vh;
            padding: 24px 16px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Barra Superior */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(201, 161, 90, 0.2);
            border-radius: 14px;
            padding: 14px 24px;
            margin-bottom: 24px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-info img {
            height: 42px;
            width: auto;
        }

        .brand-text span {
            font-size: 11px;
            color: #c9a15a;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .2s ease;
            border: none;
        }

        .btn-print {
            background: rgba(201, 161, 90, 0.15);
            color: #c9a15a;
            border: 1px solid rgba(201, 161, 90, 0.4);
        }
        .btn-print:hover {
            background: #c9a15a;
            color: #0d1420;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Tarjeta del Afiliado */
        .affiliate-card {
            background: linear-gradient(135deg, rgba(20, 32, 54, 0.8) 0%, rgba(13, 20, 32, 0.95) 100%);
            border: 1px solid rgba(201, 161, 90, 0.3);
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .affiliate-profile {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .badge-fuerza {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(201, 161, 90, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
        }

        .badge-fuerza img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .affiliate-name {
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .affiliate-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 13px;
            color: #94a3b8;
        }

        .affiliate-meta span strong {
            color: #c9a15a;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* KPIs */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 20px;
            transition: transform .2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            border-color: rgba(201, 161, 90, 0.3);
        }

        .kpi-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kpi-label i {
            color: #c9a15a;
            font-size: 16px;
        }

        .kpi-value {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            font-family: 'Consolas', monospace;
        }

        .kpi-value.gold {
            color: #e5c382;
            text-shadow: 0 0 15px rgba(201, 161, 90, 0.25);
        }

        .kpi-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Contenedor de Pestañas */
        .content-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            overflow: hidden;
        }

        .nav-tabs {
            display: flex;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0 16px;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 16px 20px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #fff;
        }

        .tab-btn.active {
            color: #c9a15a;
            border-bottom-color: #c9a15a;
        }

        .tab-panel {
            display: none;
            padding: 24px;
        }

        .tab-panel.active {
            display: block;
        }

        /* Tablas */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: rgba(255, 255, 255, 0.04);
            color: #94a3b8;
            font-weight: 600;
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .5px;
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: #cbd5e1;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
            color: #fff;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'Consolas', monospace; }

        .monto-celda {
            font-weight: 600;
            color: #e2e8f0;
        }
        .monto-vacio {
            color: #475569;
        }

        .badge-anio {
            background: rgba(201, 161, 90, 0.15);
            color: #e5c382;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
        }

        .footer-note {
            margin-top: 24px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            padding: 16px;
        }

        /* Estilos de Impresión */
        @media print {
            body { background: #fff !important; color: #000 !important; padding: 0; }
            .top-bar .actions, .nav-tabs { display: none !important; }
            .affiliate-card, .kpi-card, .content-card { border: 1px solid #ccc !important; background: #fff !important; color: #000 !important; box-shadow: none !important; }
            .affiliate-name, .kpi-value, th, td { color: #000 !important; }
            .tab-panel { display: block !important; padding: 12px 0 !important; }
            .badge-fuerza { border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Topbar -->
        <div class="top-bar">
            <div class="brand-info">
                <img src="{{ asset('img/logo.png') }}" alt="ASCINALSS">
                <div class="brand-text">
                    <span>Portal de Consulta de Aportes</span>
                </div>
            </div>
            <div class="actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fa-solid fa-print"></i> Imprimir Extracto
                </button>
                <form method="POST" action="{{ route('aportes.salir') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Salir
                    </button>
                </form>
            </div>
        </div>

        <!-- Tarjeta de Identificación del Afiliado -->
        <div class="affiliate-card">
            <div class="affiliate-profile">
                <div class="badge-fuerza">
                    @php
                        $fuerza = strtoupper($sesion['fuerza'] ?? '');
                        $imgFuerza = 'logo.png';
                        if (str_contains($fuerza, 'EJÉRCITO') || str_contains($fuerza, 'EJERCITO')) {
                            $imgFuerza = 'ejercito.png';
                        } elseif (str_contains($fuerza, 'AÉREA') || str_contains($fuerza, 'AEREA') || str_contains($fuerza, 'FAB')) {
                            $imgFuerza = 'fab.png';
                        } elseif (str_contains($fuerza, 'ARMADA')) {
                            $imgFuerza = 'armada.png';
                        }
                    @endphp
                    <img src="{{ asset('img/' . $imgFuerza) }}" alt="{{ $sesion['fuerza'] }}">
                </div>
                <div>
                    <h2 class="affiliate-name">{{ $sesion['nombre'] }}</h2>
                    <div class="affiliate-meta">
                        <span>Grado: <strong>{{ $sesion['grado'] ?? 'Socio Activo' }}</strong></span>
                        <span>Fuerza: <strong>{{ $sesion['fuerza'] ?? 'FF.AA.' }}</strong></span>
                        <span>C.I.: <strong>{{ $sesion['ci'] }}</strong></span>
                        <span>Papeleta: <strong>{{ $sesion['papeleta'] }}</strong></span>
                    </div>
                </div>
            </div>
            <div>
                <span class="status-badge">
                    Afiliado Vigente
                </span>
            </div>
        </div>

        <!-- Indicadores / KPIs -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-label">
                    <span>Total Aportado Acumulado</span>
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div class="kpi-value gold">Bs {{ number_format($totalHistoricoAportado, 2) }}</div>
                <div class="kpi-sub">Fondo de Retiro y Cesantía</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">
                    <span>Meses Cotizados</span>
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="kpi-value">{{ $totalMesesAportados }} <span style="font-size: 15px; font-weight: 500; color: #94a3b8;">meses</span></div>
                <div class="kpi-sub">Aportes registrados en planilla</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">
                    <span>Último Aporte Procesado</span>
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                @php $ultimo = $todosAportes->first(); @endphp
                <div class="kpi-value">{{ str_pad($ultimo->mes, 2, '0', STR_PAD_LEFT) }}/{{ $ultimo->anio }}</div>
                <div class="kpi-sub">Bs {{ number_format($ultimo->monto_aporte, 2) }} descontado</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">
                    <span>Promedio Mensual</span>
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                @php $promedio = $totalMesesAportados > 0 ? $totalHistoricoAportado / $totalMesesAportados : 0; @endphp
                <div class="kpi-value font-mono">Bs {{ number_format($promedio, 2) }}</div>
                <div class="kpi-sub">Aporte promedio registrado</div>
            </div>
        </div>

        <!-- Card con Tabs -->
        <div class="content-card">
            <div class="nav-tabs">
                <button class="tab-btn active" onclick="cambiarTab('matriz')">
                    <i class="fa-solid fa-table-cells"></i> Matriz Mensual por Gestión
                </button>
                <button class="tab-btn" onclick="cambiarTab('anual')">
                    <i class="fa-solid fa-chart-pie"></i> Resumen por Año
                </button>
                <button class="tab-btn" onclick="cambiarTab('historico')">
                    <i class="fa-solid fa-list-ul"></i> Detalle Histórico Completo
                </button>
            </div>

            <!-- Tab 1: Matriz Pivot Mensual -->
            <div id="tab-matriz" class="tab-panel active">
                <p style="font-size: 12px; color: #94a3b8; margin-bottom: 16px;">
                    <i class="fa-solid fa-info-circle text-gold"></i> Esta tabla muestra los aportes de cada mes (Enero a Diciembre) agrupados por año, con el total anual acumulado.
                </p>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Gestión</th>
                                <th class="text-right">Ene</th>
                                <th class="text-right">Feb</th>
                                <th class="text-right">Mar</th>
                                <th class="text-right">Abr</th>
                                <th class="text-right">May</th>
                                <th class="text-right">Jun</th>
                                <th class="text-right">Jul</th>
                                <th class="text-right">Ago</th>
                                <th class="text-right">Sep</th>
                                <th class="text-right">Oct</th>
                                <th class="text-right">Nov</th>
                                <th class="text-right">Dic</th>
                                <th class="text-right" style="color: #e5c382;">Total Año</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matrizPivot as $fila)
                                <tr>
                                    <td><span class="badge-anio">{{ $fila['anio'] }}</span></td>
                                    @for($m = 1; $m <= 12; $m++)
                                        <td class="text-right font-mono">
                                            @if($fila['meses'][$m] > 0)
                                                <span class="monto-celda">{{ number_format($fila['meses'][$m], 2) }}</span>
                                            @else
                                                <span class="monto-vacio">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="text-right font-mono" style="font-weight: 700; color: #e5c382;">
                                        Bs {{ number_format($fila['total_anual'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Resumen Anual -->
            <div id="tab-anual" class="tab-panel">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Gestión</th>
                                <th class="text-center">Meses Aportados</th>
                                <th class="text-right">Sueldo Ganado Promedio</th>
                                <th class="text-right">Total Aportado en la Gestión</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumenAnual as $anio)
                                <tr>
                                    <td><span class="badge-anio">{{ $anio->anio }}</span></td>
                                    <td class="text-center font-mono">{{ $anio->meses }} / 12 meses</td>
                                    <td class="text-right font-mono">Bs {{ number_format($anio->prom_ganado, 2) }}</td>
                                    <td class="text-right font-mono" style="font-weight: 700; color: #e5c382;">
                                        Bs {{ number_format($anio->total_anual, 2) }}
                                    </td>
                                    <td class="text-center">
                                        @if($anio->meses == 12)
                                            <span style="color: #34d399; font-size: 11px;"><i class="fa-solid fa-circle-check"></i> Completo</span>
                                        @else
                                            <span style="color: #fbbf24; font-size: 11px;"><i class="fa-solid fa-clock"></i> En curso</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Detalle Histórico -->
            <div id="tab-historico" class="tab-panel">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>Mes / Gestión</th>
                                <th class="text-right">Total Ganado (Planilla)</th>
                                <th class="text-right">Monto Aporte</th>
                                <th class="text-right">Líquido Pagable</th>
                                <th class="text-center">Estado Planilla</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $mesesNombres = [
                                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                ];
                            @endphp
                            @foreach($todosAportes as $aporte)
                                <tr>
                                    <td><code>{{ $aporte->anio }}-{{ str_pad($aporte->mes, 2, '0', STR_PAD_LEFT) }}</code></td>
                                    <td><strong>{{ $mesesNombres[$aporte->mes] ?? $aporte->mes }} {{ $aporte->anio }}</strong></td>
                                    <td class="text-right font-mono">Bs {{ number_format($aporte->total_ganado, 2) }}</td>
                                    <td class="text-right font-mono" style="font-weight: 700; color: #34d399;">
                                        + Bs {{ number_format($aporte->monto_aporte, 2) }}
                                    </td>
                                    <td class="text-right font-mono">Bs {{ number_format($aporte->liquido_pagable, 2) }}</td>
                                    <td class="text-center">
                                        <span style="background: rgba(16, 185, 129, 0.1); color: #34d399; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                                            CONCILIADO
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer-note">
            Información confidencial de aportes de la Asociación Nacional de Suboficiales y Sargentos de las FF.AA. de la Nación (ASCINALSS).<br>
            Corte sincronizado al: {{ \Carbon\Carbon::parse($ultimaActualizacion)->format('d/m/Y') }} | Generado automáticamente.
        </div>
    </div>

    <script>
        function cambiarTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

            event.currentTarget.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        }
    </script>
</body>
</html>
