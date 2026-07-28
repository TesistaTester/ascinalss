<?php
// database/migrations/2026_07_15_000009_crear_inicio_slides.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inicio_slides', function (Blueprint $table) {
            $table->increments('ini_id');
            $table->string('ini_titulo')->nullable();
            $table->string('ini_subtitulo')->nullable();
            $table->string('ini_imagen');
            $table->integer('ini_orden')->default(0);
            $table->boolean('ini_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inicio_slides');
    }
};