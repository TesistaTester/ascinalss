<?php
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model implements AuthenticatableContract
{
    use HasFactory, SoftDeletes, Authenticatable;

    protected $table = 'usuarios';
    protected $primaryKey = 'usu_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Roles (usu_rol)
    public const ROL_ADMIN = 1;
    public const ROL_EDITOR = 2;
    public const ROL_DIRECTORIO = 3;

    protected $fillable = [
        'usu_rol',
        'usu_nombre',
        'password',
        'usu_nombre_completo',
        'usu_estado',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'usu_rol' => 'integer',
        'usu_estado' => 'boolean',
    ];

    // Sin columna remember_token, igual que en el proyecto de incidencias
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // no-op: no hay columna remember_token
    }

    public function getRememberTokenName()
    {
        return '';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // --- Relaciones ---

    public function comunicados(): HasMany
    {
        return $this->hasMany(Comunicado::class, 'com_usuario_id', 'usu_id');
    }

    // --- Helpers ---

    public function esAdmin(): bool
    {
        return $this->usu_rol === self::ROL_ADMIN;
    }

    public function esEditor(): bool
    {
        return $this->usu_rol === self::ROL_EDITOR;
    }

    public function esDirectorio(): bool
    {
        return $this->usu_rol === self::ROL_DIRECTORIO;
    }
}