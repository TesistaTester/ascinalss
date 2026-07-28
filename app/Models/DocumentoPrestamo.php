<?php
// app/Models/DocumentoPrestamo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoPrestamo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documentos_prestamo';
    protected $primaryKey = 'doc_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Tipos (doc_tipo)
    public const TIPO_REQUISITOS = 'requisitos';
    public const TIPO_CONTRATO = 'contrato';
    public const TIPO_FORMULARIO = 'formulario';

    protected $fillable = [
        'doc_categoria_id',
        'doc_tipo',
        'doc_etiqueta',
        'doc_pdf_archivo',
        'doc_orden',
        'doc_estado',
    ];

    protected $casts = [
        'doc_orden' => 'integer',
        'doc_estado' => 'boolean',
    ];

    // --- Relaciones ---

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaPrestamo::class, 'doc_categoria_id', 'cat_id');
    }
}