<?php
// database/migrations/2026_07_15_000003_crear_convenios.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('convenios', function (Blueprint $table) {
            $table->increments('conv_id');
            $table->string('conv_titulo');
            $table->text('conv_descripcion')->nullable();
            $table->string('conv_empresa')->nullable();
            $table->string('conv_logo')->nullable();
            $table->string('conv_pdf_archivo')->nullable();
            $table->integer('conv_orden')->default(0);
            $table->boolean('conv_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};