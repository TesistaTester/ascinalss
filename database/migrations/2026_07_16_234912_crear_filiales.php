<?php
// database/migrations/2026_07_15_000004_crear_filiales.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('filiales', function (Blueprint $table) {
            $table->increments('fil_id');
            $table->string('fil_nombre');
            $table->string('fil_ciudad')->nullable();
            $table->string('fil_direccion')->nullable();
            $table->string('fil_telefono')->nullable();
            $table->integer('fil_orden')->default(0);
            $table->boolean('fil_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filiales');
    }
};