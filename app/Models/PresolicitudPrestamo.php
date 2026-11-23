<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresolicitudPrestamo extends Model
{
    use SoftDeletes;
    protected $table = 'presolicitudes_prestamo';
    protected $primaryKey = 'pre_id';
    protected $guarded = [];
    protected $casts = [
        'pre_formulario' => 'encrypted:array', 'pre_simulacion' => 'encrypted:array',
        'pre_original' => 'encrypted:array', 'pre_observaciones' => 'encrypted',
        'pre_aceptado_en' => 'datetime', 'pre_documentacion_validada_en' => 'datetime',
    ];
    public const ESTADOS = ['RECIBIDA', 'EN_REVISION', 'OBSERVADA', 'LISTA_PARA_ATENCION'];
    public function revisiones()
    {
        return $this->hasMany(RevisionPresolicitud::class, 'rev_presolicitud_id', 'pre_id')->orderByDesc('rev_version');
    }
}
