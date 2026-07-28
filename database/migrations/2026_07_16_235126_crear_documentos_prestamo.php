<?php
// database/migrations/2026_07_15_000008_crear_documentos_prestamo.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos_prestamo', function (Blueprint $table) {
            $table->increments('doc_id');
            $table->unsignedInteger('doc_categoria_id');
            $table->enum('doc_tipo', ['requisitos', 'contrato', 'formulario']);
            $table->string('doc_etiqueta'); // ej. "Ver Requisitos"
            $table->string('doc_pdf_archivo');
            $table->integer('doc_orden')->default(0);
            $table->boolean('doc_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('doc_categoria_id')
                  ->references('cat_id')->on('categorias_prestamo')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_prestamo');
    }
};