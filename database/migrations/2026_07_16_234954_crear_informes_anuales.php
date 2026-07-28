<?php
// database/migrations/2026_07_15_000006_crear_informes_anuales.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('informes_anuales', function (Blueprint $table) {
            $table->increments('inf_id');
            $table->year('inf_anio');
            $table->string('inf_titulo');
            $table->string('inf_pdf_archivo');
            $table->date('inf_fecha_publicacion')->nullable();
            $table->boolean('inf_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes_anuales');
    }
};