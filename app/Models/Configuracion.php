<?php
// app/Models/Configuracion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuracion';
    protected $primaryKey = 'cfg_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'cfg_clave',
        'cfg_valor',
    ];

    // --- Helper estático para leer config por clave, tipo Setting Model ---

    public static function obtener(string $clave, $default = null)
    {
        return static::where('cfg_clave', $clave)->value('cfg_valor') ?? $default;
    }

    public static function establecer(string $clave, $valor): void
    {
        static::updateOrCreate(
            ['cfg_clave' => $clave],
            ['cfg_valor' => $valor]
        );
    }
}