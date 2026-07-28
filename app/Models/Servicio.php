<?php
// app/Models/Servicio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicios';
    protected $primaryKey = 'ser_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ser_titulo',
        'ser_descripcion',
        'ser_imagen',
        'ser_direccion',
        'ser_telefono_whatsapp',
        'ser_capacidad',
        'ser_orden',
        'ser_estado',
    ];

    protected $casts = [
        'ser_capacidad' => 'integer',
        'ser_orden' => 'integer',
        'ser_estado' => 'boolean',
    ];
}