<?php
// database/migrations/2026_07_15_000005_crear_comunicados.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comunicados', function (Blueprint $table) {
            $table->increments('com_id');
            $table->unsignedInteger('com_usuario_id')->nullable();
            $table->string('com_titulo');
            $table->longText('com_contenido');
            $table->string('com_imagen')->nullable();
            $table->string('com_pdf_archivo')->nullable();
            $table->enum('com_tipo', ['normal', 'modal', 'destacado'])->default('normal');
            $table->date('com_fecha_publicacion');
            $table->date('com_fecha_expiracion')->nullable();
            $table->boolean('com_fijado')->default(0);
            $table->boolean('com_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('com_usuario_id')
                  ->references('usu_id')->on('usuarios')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunicados');
    }
};