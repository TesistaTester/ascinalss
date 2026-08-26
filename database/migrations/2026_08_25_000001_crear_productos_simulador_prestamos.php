<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productos_simulador', function (Blueprint $table) {
            $table->increments('pro_id');
            $table->string('pro_codigo', 20)->unique();
            $table->string('pro_nombre');
            $table->text('pro_descripcion')->nullable();
            $table->decimal('pro_monto_minimo', 14, 2)->nullable();
            $table->decimal('pro_monto_maximo', 14, 2)->nullable();
            $table->string('pro_moneda', 3)->default('BOB');
            $table->unsignedInteger('pro_plazo_minimo_meses')->nullable();
            $table->unsignedInteger('pro_plazo_maximo_meses')->nullable();
            $table->boolean('pro_requiere_garantes')->default(false);
            $table->unsignedTinyInteger('pro_cantidad_garantes')->nullable();
            $table->boolean('pro_considera_antiguedad')->default(false);
            $table->boolean('pro_activo')->default(true);
            $table->date('pro_vigente_desde')->nullable();
            $table->date('pro_vigente_hasta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('parametros_financieros', function (Blueprint $table) {
            $table->increments('par_id');
            $table->unsignedInteger('par_producto_id');
            $table->decimal('par_tasa', 10, 6)->nullable();
            $table->string('par_tipo_tasa', 30)->nullable();
            $table->string('par_base_interes', 30)->nullable();
            $table->string('par_metodo_amortizacion', 30)->default('PENDIENTE');
            $table->string('par_frecuencia_pago', 20)->default('MENSUAL');
            $table->unsignedTinyInteger('par_decimales_redondeo')->default(2);
            $table->string('par_estado', 20)->default('PENDIENTE');
            $table->date('par_vigente_desde')->nullable();
            $table->date('par_vigente_hasta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('par_producto_id')->references('pro_id')->on('productos_simulador')->cascadeOnDelete();
        });

        Schema::create('reglas_capacidad_prestamo', function (Blueprint $table) {
            $table->increments('reg_id');
            $table->unsignedInteger('reg_producto_id');
            $table->boolean('reg_usar_porcentaje_maximo')->default(false);
            $table->decimal('reg_porcentaje_maximo_liquido', 8, 6)->nullable();
            $table->boolean('reg_usar_liquido_minimo')->default(false);
            $table->decimal('reg_liquido_minimo_residual', 14, 2)->nullable();
            $table->boolean('reg_usar_factor_seguridad')->default(false);
            $table->decimal('reg_factor_seguridad', 8, 6)->nullable();
            $table->boolean('reg_considerar_refinanciamiento')->default(false);
            $table->boolean('reg_liberar_cuota_refinanciada')->default(false);
            $table->string('reg_estado', 20)->default('PENDIENTE');
            $table->date('reg_vigente_desde')->nullable();
            $table->date('reg_vigente_hasta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('reg_producto_id')->references('pro_id')->on('productos_simulador')->cascadeOnDelete();
        });

        Schema::create('historial_condiciones_prestamo', function (Blueprint $table) {
            $table->increments('his_id');
            $table->unsignedInteger('his_producto_id');
            $table->string('his_parametro');
            $table->text('his_valor')->nullable();
            $table->string('his_tipo', 30)->nullable();
            $table->date('his_vigente_desde')->nullable();
            $table->date('his_vigente_hasta')->nullable();
            $table->string('his_fuente')->nullable();
            $table->string('his_estado', 20)->default('PENDIENTE');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('his_producto_id')->references('pro_id')->on('productos_simulador')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_condiciones_prestamo');
        Schema::dropIfExists('reglas_capacidad_prestamo');
        Schema::dropIfExists('parametros_financieros');
        Schema::dropIfExists('productos_simulador');
    }
};
