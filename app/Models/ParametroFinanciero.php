<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParametroFinanciero extends Model
{
    use SoftDeletes;
    protected $table = 'parametros_financieros';
    protected $primaryKey = 'par_id';
    protected $guarded = [];
    protected $casts = ['par_tasa' => 'decimal:6', 'par_vigente_desde' => 'date', 'par_vigente_hasta' => 'date'];
}
