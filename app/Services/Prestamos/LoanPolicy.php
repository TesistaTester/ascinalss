<?php
namespace App\Services\Prestamos;

use App\Models\ProductoSimulador;

class LoanPolicy
{
    public const CONDICIONES_VERSION = '2026-09-10-v1';
    public const CONDICIONES = 'Resultado referencial. La simulación estima cuotas y verifica determinadas condiciones a partir de los datos ingresados. Un resultado favorable no constituye aprobación de préstamo ni acredita el cumplimiento de todos los requisitos. ASCINALSS deberá verificar la identidad, documentación, historial de obligaciones, aportes y rendimientos disponibles (DAARO), garantías y demás condiciones aplicables. Inicio del plazo de atención. El envío de una presolicitud no inicia los plazos de tramitación previstos en el artículo 24 del Reglamento de Préstamos. Estos se computan a partir de que el operador valide que la documentación exigida está completa y sin observaciones, omisiones ni alteraciones.';

    public static function defaults(string $codigo): array
    {
        return [
            'fuente' => 'Reglamento de Préstamo 15-07-2026, arts. 12 a 26 y 30 a 31',
            'permitir_aproximacion' => true,
            'usar_capacidad_referencial' => true,
            'porcentaje_referencial' => .75, 'residual_referencial' => 825,
            'limite_servicio_meses' => 420,
            'aportes_minimos' => $codigo === 'EMERG' ? 6 : ($codigo === 'REG_SIN' ? 61 : 1),
            'cargo_inicial' => $codigo === 'EMERG' ? 0 : 70,
            'cargo_mensual' => 0,
            'cargos_pendientes' => $codigo === 'EMERG'
                ? 'No incluye el cargo administrativo de Emergencia de Bs 35, cuya periodicidad debe precisarse, ni retención del Ministerio de Defensa.'
                : 'No incluye retención del Ministerio de Defensa ni gastos notariales no configurados.',
            'escalas' => match ($codigo) {
                'EMERG' => [[6,60,25000],[61,120,50000],[121,180,60000],[181,240,70000],[241,300,80000]],
                'REG_SIN' => [[61,120,50000],[121,180,80000],[181,240,110000],[241,300,140000]],
                default => [],
            },
        ];
    }
    public static function for(ProductoSimulador $producto): array
    {
        return array_replace(self::defaults($producto->pro_codigo), $producto->pro_reglas_simulacion ?? []);
    }
    public static function fields(): array
    {
        return [
            'nombres' => 'Nombres y apellidos', 'fecha_nacimiento' => 'Fecha de nacimiento',
            'ci' => 'Cédula de identidad', 'grado' => 'Grado', 'estado_civil' => 'Estado civil',
            'papeleta' => 'Código de papeleta', 'domicilio' => 'Dirección de domicilio',
            'telefono' => 'Número personal', 'telefono_corporativo' => 'Número corporativo',
            'email' => 'Correo electrónico', 'fuerza' => 'Fuerza a la que pertenece',
            'destino' => 'Destino actual', 'telefono_oficina' => 'Teléfono de oficina',
            'banco' => 'Banco', 'cuenta' => 'Número de cuenta', 'objeto' => 'Objeto del préstamo',
        ];
    }
    public static function garanteFields(): array
    {
        return array_diff_key(self::fields(), array_flip(['banco','cuenta','objeto']));
    }
    public static function formularioRules(bool $garantes): array
    {
        $rules = [];
        foreach (self::fields() as $key => $label) {
            $rules['formulario.'.$key] = (in_array($key, ['nombres','ci','telefono','objeto']) ? 'required' : 'nullable').'|string|max:255';
        }
        $rules['formulario.email'] = 'nullable|email|max:255';
        $rules['formulario.fecha_nacimiento'] = 'nullable|date|before:today';
        $rules['formulario.garantes'] = $garantes ? 'nullable|array|max:2' : 'exclude';
        foreach (self::garanteFields() as $key => $label) {
            $rules['formulario.garantes.*.'.$key] = 'nullable|string|max:255';
        }
        $rules['formulario.garantes.*.fecha_nacimiento'] = 'nullable|date|before:today';
        $rules['formulario.garantes.*.email'] = 'nullable|email|max:255';
        return $rules;
    }
    public static function inputRules(): array
    {
        return [
            'producto' => 'required|in:REG_GAR,REG_SIN,EMERG,INIC',
            'monto_solicitado' => 'required|numeric|min:1|max:10000000',
            'plazo' => 'required|integer|min:1|max:300',
            'liquido_pagable' => 'required|numeric|min:0|max:1000000',
            'situacion' => 'required|in:ACTIVO,PASIVO',
            'anios_servicio' => 'required|integer|min:0|max:60',
            'meses_servicio' => 'required|integer|min:0|max:11',
            'socio_habilitado' => 'required|boolean', 'mora' => 'required|boolean',
            'aportes' => 'required_if:producto,REG_SIN,EMERG,INIC|nullable|integer|min:0|max:720',
            'aportes_continuos' => 'required_if:producto,EMERG|nullable|boolean',
            'recien_egresado' => 'required_if:producto,INIC|nullable|boolean',
            'dos_garantes' => 'required_if:producto,REG_GAR|nullable|boolean',
            'operacion' => 'required|in:NUEVO,REFINANCIAMIENTO',
            'saldo_modalidad' => 'required|numeric|min:0|max:10000000',
            'saldo_cancelar' => 'required_if:operacion,REFINANCIAMIENTO|nullable|numeric|min:0|max:10000000',
            'cuota_liberada' => 'required_if:operacion,REFINANCIAMIENTO|nullable|numeric|min:0|max:1000000',
            'emergencias_mes' => 'required_if:producto,EMERG|nullable|integer|min:0|max:100',
            'bonos_temporales' => 'nullable|numeric|min:0|lte:liquido_pagable',
            'fecha_inicio' => 'nullable|date|after_or_equal:today|before_or_equal:'.now()->addYear()->toDateString(),
        ];
    }
}
