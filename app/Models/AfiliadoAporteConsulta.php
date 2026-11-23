<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfiliadoAporteConsulta extends Model
{
    use HasFactory;

    protected $table = 'afiliado_aportes_consulta';

    protected $fillable = [
        'ci',
        'numero_papeleta',
        'nombre_completo',
        'fuerza',
        'grado',
        'anio',
        'mes',
        'total_ganado',
        'monto_aporte',
        'liquido_pagable',
        'actualizado_al',
    ];

    protected $casts = [
        'anio' => 'integer',
        'mes' => 'integer',
        'total_ganado' => 'decimal:2',
        'monto_aporte' => 'decimal:2',
        'liquido_pagable' => 'decimal:2',
        'actualizado_al' => 'date',
    ];

    /**
     * Scope para consultar por CI y Papeleta
     */
    public function scopePorAfiliado($query, string $ci, string $papeleta)
    {
        return $query->where('ci', trim($ci))
                     ->where('numero_papeleta', trim($papeleta));
    }
}
