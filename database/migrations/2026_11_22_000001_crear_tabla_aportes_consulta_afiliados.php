<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('afiliado_aportes_consulta', function (Blueprint $table) {
            $table->id();
            $table->string('ci', 20)->index();
            $table->string('numero_papeleta', 20)->index();
            $table->string('nombre_completo', 200);
            $table->string('fuerza', 50)->nullable();
            $table->string('grado', 50)->nullable();
            $table->smallInteger('anio')->index();
            $table->smallInteger('mes');
            $table->decimal('total_ganado', 12, 2)->default(0);
            $table->decimal('monto_aporte', 12, 2);
            $table->decimal('liquido_pagable', 12, 2)->default(0);
            $table->date('actualizado_al')->nullable();
            $table->timestamps();

            // Índice compuesto para acelerar las búsquedas por afiliado y gestión
            $table->index(['ci', 'numero_papeleta']);
            $table->index(['ci', 'anio', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afiliado_aportes_consulta');
    }
};
