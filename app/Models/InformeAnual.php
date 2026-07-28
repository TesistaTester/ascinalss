<?php
// app/Models/InformeAnual.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformeAnual extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'informes_anuales';
    protected $primaryKey = 'inf_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'inf_anio',
        'inf_titulo',
        'inf_pdf_archivo',
        'inf_fecha_publicacion',
        'inf_estado',
    ];

    protected $casts = [
        'inf_anio' => 'integer',
        'inf_fecha_publicacion' => 'date',
        'inf_estado' => 'boolean',
    ];
}