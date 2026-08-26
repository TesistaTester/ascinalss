<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCondicionPrestamo extends Model
{
    use SoftDeletes;
    protected $table = 'historial_condiciones_prestamo';
    protected $primaryKey = 'his_id';
    protected $guarded = [];
    protected $casts = ['his_vigente_desde' => 'date', 'his_vigente_hasta' => 'date'];
}
