<?php
// database/migrations/2026_07_15_000007_crear_categorias_prestamo.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categorias_prestamo', function (Blueprint $table) {
            $table->increments('cat_id');
            $table->string('cat_nombre'); // Emergencia, Regulares con Garantes, D.A.A.R.O., etc.
            $table->string('cat_slug')->unique();
            $table->text('cat_descripcion')->nullable();
            $table->string('cat_icono')->nullable();
            $table->integer('cat_orden')->default(0);
            $table->boolean('cat_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_prestamo');
    }
};