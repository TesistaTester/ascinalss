<?php
// app/Models/InicioSlide.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InicioSlide extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inicio_slides';
    protected $primaryKey = 'ini_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ini_titulo',
        'ini_subtitulo',
        'ini_imagen',
        'ini_orden',
        'ini_estado',
    ];

    protected $casts = [
        'ini_orden' => 'integer',
        'ini_estado' => 'boolean',
    ];
}