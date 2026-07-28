<?php
// app/Models/Comunicado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunicado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comunicados';
    protected $primaryKey = 'com_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Tipos (com_tipo)
    public const TIPO_NORMAL = 'normal';
    public const TIPO_MODAL = 'modal';
    public const TIPO_DESTACADO = 'destacado';
    public const TIPO_NOVEDAD = 'novedad';

    protected $fillable = [
        'com_usuario_id',
        'com_titulo',
        'com_contenido',
        'com_imagen',
        'com_pdf_archivo',
        'com_video_url',
        'com_pptx_archivo',
        'com_tipo',
        'com_fecha_publicacion',
        'com_fecha_expiracion',
        'com_fijado',
        'com_estado',
    ];

    protected $casts = [
        'com_fecha_publicacion' => 'date',
        'com_fecha_expiracion' => 'date',
        'com_fijado' => 'boolean',
        'com_estado' => 'boolean',
    ];

    // --- Relaciones ---

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'com_usuario_id', 'usu_id');
    }

    // --- Helpers ---

    public function esModal(): bool
    {
        return $this->com_tipo === self::TIPO_MODAL;
    }

    public function esNovedad(): bool
    {
        return $this->com_tipo === self::TIPO_NOVEDAD;
    }

    public function vigente(): bool
    {
        return is_null($this->com_fecha_expiracion)
            || $this->com_fecha_expiracion->isFuture();
    }
}