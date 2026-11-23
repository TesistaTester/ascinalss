<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos_simulador', function (Blueprint $table) {
            $table->json('pro_reglas_simulacion')->nullable();
        });
        Schema::create('presolicitudes_prestamo', function (Blueprint $table) {
            $table->increments('pre_id');
            $table->uuid('pre_referencia')->unique();
            $table->uuid('pre_simulacion_id')->unique();
            $table->string('pre_estado', 30)->default('RECIBIDA');
            $table->unsignedInteger('pre_version')->default(1);
            $table->longText('pre_formulario');
            $table->longText('pre_simulacion');
            $table->longText('pre_original');
            $table->text('pre_observaciones')->nullable();
            $table->text('pre_condiciones');
            $table->timestamp('pre_aceptado_en');
            $table->timestamp('pre_documentacion_validada_en')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('revisiones_presolicitud', function (Blueprint $table) {
            $table->increments('rev_id');
            $table->unsignedInteger('rev_presolicitud_id');
            $table->unsignedInteger('rev_usuario_id');
            $table->unsignedInteger('rev_version');
            $table->longText('rev_datos');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('rev_presolicitud_id')->references('pre_id')->on('presolicitudes_prestamo');
            $table->foreign('rev_usuario_id')->references('usu_id')->on('usuarios');
            $table->unique(['rev_presolicitud_id', 'rev_version']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('revisiones_presolicitud');
        Schema::dropIfExists('presolicitudes_prestamo');
        Schema::table('productos_simulador', fn (Blueprint $table) => $table->dropColumn('pro_reglas_simulacion'));
    }
};
