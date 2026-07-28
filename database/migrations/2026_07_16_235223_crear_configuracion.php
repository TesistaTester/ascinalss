<?php
// database/migrations/2026_07_15_000010_crear_configuracion.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->increments('cfg_id');
            $table->string('cfg_clave')->unique(); // ej. 'telefono_prestamos'
            $table->text('cfg_valor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};