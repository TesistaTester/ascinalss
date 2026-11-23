<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RevisionPresolicitud extends Model
{
    use SoftDeletes;
    protected $table = 'revisiones_presolicitud';
    protected $primaryKey = 'rev_id';
    protected $guarded = [];
    protected $casts = ['rev_datos' => 'encrypted:array'];
    public function usuario() { return $this->belongsTo(Usuario::class, 'rev_usuario_id', 'usu_id')->withTrashed(); }
}
