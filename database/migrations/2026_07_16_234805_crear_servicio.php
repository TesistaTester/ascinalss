<?php
// database/migrations/2026_07_15_000002_crear_servicios.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->increments('ser_id');
            $table->string('ser_titulo');
            $table->text('ser_descripcion')->nullable();
            $table->string('ser_imagen')->nullable();
            $table->string('ser_direccion')->nullable();
            $table->string('ser_telefono_whatsapp')->nullable();
            $table->integer('ser_capacidad')->nullable();
            $table->integer('ser_orden')->default(0);
            $table->boolean('ser_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};