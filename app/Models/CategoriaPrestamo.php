<?php
// app/Models/CategoriaPrestamo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaPrestamo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categorias_prestamo';
    protected $primaryKey = 'cat_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cat_nombre',
        'cat_slug',
        'cat_descripcion',
        'cat_icono',
        'cat_orden',
        'cat_estado',
    ];

    protected $casts = [
        'cat_orden' => 'integer',
        'cat_estado' => 'boolean',
    ];

    // --- Relaciones ---

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoPrestamo::class, 'doc_categoria_id', 'cat_id');
    }
}