<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoSimulador extends Model
{
    use SoftDeletes;

    protected $table = 'productos_simulador';
    protected $primaryKey = 'pro_id';
    protected $guarded = [];

    protected $casts = [
        'pro_monto_minimo' => 'decimal:2', 'pro_monto_maximo' => 'decimal:2',
        'pro_requiere_garantes' => 'boolean', 'pro_considera_antiguedad' => 'boolean',
        'pro_activo' => 'boolean', 'pro_vigente_desde' => 'date', 'pro_vigente_hasta' => 'date',
    ];

    public function parametro(): HasOne
    {
        return $this->hasOne(ParametroFinanciero::class, 'par_producto_id', 'pro_id')->latestOfMany('par_id');
    }

    public function reglaCapacidad(): HasOne
    {
        return $this->hasOne(ReglaCapacidadPrestamo::class, 'reg_producto_id', 'pro_id')->latestOfMany('reg_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialCondicionPrestamo::class, 'his_producto_id', 'pro_id');
    }
}
