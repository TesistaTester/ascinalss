<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReglaCapacidadPrestamo extends Model
{
    use SoftDeletes;
    protected $table = 'reglas_capacidad_prestamo';
    protected $primaryKey = 'reg_id';
    protected $guarded = [];
    protected $casts = [
        'reg_usar_porcentaje_maximo' => 'boolean', 'reg_usar_liquido_minimo' => 'boolean',
        'reg_usar_factor_seguridad' => 'boolean', 'reg_considerar_refinanciamiento' => 'boolean',
        'reg_liberar_cuota_refinanciada' => 'boolean', 'reg_vigente_desde' => 'date', 'reg_vigente_hasta' => 'date',
    ];
}
