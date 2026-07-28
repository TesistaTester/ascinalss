<?php
// app/Models/Convenio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convenio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'convenios';
    protected $primaryKey = 'conv_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'conv_titulo',
        'conv_descripcion',
        'conv_empresa',
        'conv_logo',
        'conv_pdf_archivo',
        'conv_orden',
        'conv_estado',
    ];

    protected $casts = [
        'conv_orden' => 'integer',
        'conv_estado' => 'boolean',
    ];
}