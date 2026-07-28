<?php
// app/Models/Filial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'filiales';
    protected $primaryKey = 'fil_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'fil_nombre',
        'fil_ciudad',
        'fil_direccion',
        'fil_telefono',
        'fil_orden',
        'fil_estado',
    ];

    protected $casts = [
        'fil_orden' => 'integer',
        'fil_estado' => 'boolean',
    ];
}