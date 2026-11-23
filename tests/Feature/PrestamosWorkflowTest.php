<?php
namespace Tests\Feature;

use App\Models\PresolicitudPrestamo;
use App\Models\ProductoSimulador;
use App\Models\Usuario;
use App\Services\Prestamos\LoanPolicy;
use App\Services\Prestamos\LoanSimulationService;
use Database\Seeders\ProductoSimuladorSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PrestamosWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key'=>'base64:'.base64_encode(str_repeat('a',32)), 'database.default'=>'sqlite',
            'database.connections.sqlite.database'=>':memory:', 'session.driver'=>'array', 'cache.default'=>'array']);
        DB::purge('sqlite');
        foreach (['2025_12_26_120831_crear_usuarios.php','2026_08_25_000001_crear_productos_simulador_prestamos.php','2026_09_10_000001_ampliar_simulador_presolicitudes.php'] as $file) {
            (require database_path('migrations/'.$file))->up();
        }
        (new ProductoSimuladorSeeder())->run();
    }
    private function entrada(array $cambios=[]): array
    {
        return array_replace(['producto'=>'REG_GAR','monto_solicitado'=>50000,'plazo'=>120,'liquido_pagable'=>6000,
            'situacion'=>'ACTIVO','anios_servicio'=>10,'meses_servicio'=>0,'socio_habilitado'=>1,'mora'=>0,
            'dos_garantes'=>1,'operacion'=>'NUEVO','saldo_modalidad'=>0],$cambios);
    }
    private function evaluar(array $datos): array
    {
        return app(LoanSimulationService::class)->simular(ProductoSimulador::where('pro_codigo',$datos['producto'])->firstOrFail(),$datos);
    }
    private function usuario(int $rol): Usuario
    {
        return Usuario::create(['usu_rol'=>$rol,'usu_nombre'=>'usuario'.$rol,'usu_nombre_completo'=>'Evaluador prueba','password'=>Hash::make('test-password'),'usu_estado'=>true]);
    }
    private function envio(): array
    {
        $response=$this->postJson(route('prestamos.simular'),$this->entrada())->assertOk()->assertJsonPath('estado','COMPATIBLE');
        return ['simulacion_id'=>$response->json('simulacion_id'),'acepta_condiciones'=>1,
            'formulario'=>['nombres'=>'Persona de prueba','ci'=>'TEST-123','telefono'=>'70000000','objeto'=>'Vivienda']];
    }
    public function test_plan_concilia_capital_saldo_y_totales(): void
    {
        $s=$this->evaluar($this->entrada(['monto_solicitado'=>245000,'plazo'=>180,'liquido_pagable'=>15000]));
        $this->assertCount(180,$s['plan']);
        $this->assertEquals(245000,$s['totales']['capital']);
        $this->assertEquals(0,end($s['plan'])['saldo']);
        $this->assertEqualsWithDelta($s['totales']['capital']+$s['totales']['interes']+$s['totales']['cargos'],$s['totales']['cuotas'],.001);
        $this->assertTrue($s['puede_enviar']);
    }
    public function test_escalas_servicio_mora_y_casos_particulares(): void
    {
        $s=$this->evaluar($this->entrada(['producto'=>'EMERG','monto_solicitado'=>26000,'plazo'=>60,'aportes'=>60,'aportes_continuos'=>1,'emergencias_mes'=>0]));
        $this->assertSame('INCUMPLE_REQUISITO',$s['estado']);
        $this->assertEquals(25000,$s['maximo_modalidad']);
        $this->assertSame('INCUMPLE_REQUISITO',$this->evaluar($this->entrada(['anios_servicio'=>34,'plazo'=>13]))['estado']);
        $this->assertSame('INCUMPLE_REQUISITO',$this->evaluar($this->entrada(['mora'=>1]))['estado']);
        $this->assertSame('REQUIERE_EVALUACION',$this->evaluar($this->entrada(['situacion'=>'PASIVO']))['estado']);
        $this->assertSame('SUPERA_CAPACIDAD',$this->evaluar($this->entrada(['liquido_pagable'=>850]))['estado']);
    }
    public function test_refinanciamiento_libera_cuota_y_descuenta_liquidacion(): void
    {
        $s=$this->evaluar($this->entrada(['liquido_pagable'=>1000,'operacion'=>'REFINANCIAMIENTO','cuota_liberada'=>2000,'saldo_cancelar'=>10000]));
        $this->assertEquals(2175,$s['cuota_maxima_estimada']);
        $this->assertEquals(39930,$s['neto_estimado']);
    }
    public function test_capacidad_pendiente_no_se_presenta_como_maximo_personal(): void
    {
        $p=ProductoSimulador::where('pro_codigo','REG_GAR')->first();
        $p->update(['pro_reglas_simulacion'=>['usar_capacidad_referencial'=>false]]);
        $s=$this->evaluar($this->entrada());
        $this->assertNull($s['monto_maximo_estimado']); $this->assertFalse($s['puede_enviar']);
    }
    public function test_validacion_condicional_del_servidor(): void
    {
        $this->postJson(route('prestamos.simular'),$this->entrada(['producto'=>'EMERG']))->assertUnprocessable()->assertJsonValidationErrors(['aportes','aportes_continuos','emergencias_mes']);
    }
    public function test_envio_exige_aceptacion_y_guarda_original_cifrado_sin_duplicar(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $datos=$this->envio();
        $this->postJson(route('prestamos.enviar'),array_replace($datos,['acepta_condiciones'=>0]))->assertUnprocessable();
        $this->postJson(route('prestamos.enviar'),$datos)->assertCreated();
        $this->postJson(route('prestamos.enviar'),$datos)->assertOk();
        $this->assertSame(1,PresolicitudPrestamo::count());
        $pre=PresolicitudPrestamo::first();
        $this->assertSame('Persona de prueba',$pre->pre_original['formulario']['nombres']);
        $this->assertStringNotContainsString('Persona de prueba',DB::table('presolicitudes_prestamo')->value('pre_formulario'));
        $this->assertNull($pre->pre_documentacion_validada_en);
    }
    public function test_no_acepta_resultado_manipulado_ni_de_otra_sesion(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $r=$this->postJson(route('prestamos.simular'),$this->entrada(['mora'=>1]))->assertOk();
        $this->postJson(route('prestamos.enviar'),['simulacion_id'=>$r->json('simulacion_id'),'puede_enviar'=>true])->assertUnprocessable();
        $this->get(route('prestamos.plan','11111111-1111-4111-8111-111111111111'))->assertNotFound();
    }
    public function test_evalua_versiona_e_imprime_sin_acceso_a_configuracion(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $this->postJson(route('prestamos.enviar'),$this->envio())->assertCreated();
        $pre=PresolicitudPrestamo::first();
        $this->actingAs($this->usuario(4));
        $this->get(route('presolicitudes.index'))->assertOk();
        $this->get(route('presolicitudes.show',$pre))->assertOk();
        $this->get(route('simulador-prestamos.index'))->assertForbidden();
        $this->get(route('dashboard'))->assertForbidden();
        $datos=['version'=>1,'estado'=>'EN_REVISION','formulario'=>array_replace($pre->pre_formulario,['nombres'=>'Nombre corregido']),'observaciones'=>'Verificar boleta'];
        $this->put(route('presolicitudes.update',$pre),$datos)->assertRedirect();
        $pre->refresh();
        $this->assertEquals(2,$pre->pre_version); $this->assertCount(1,$pre->revisiones);
        $this->assertSame('Persona de prueba',$pre->pre_original['formulario']['nombres']);
        $this->get(route('presolicitudes.imprimir',$pre))->assertOk()->assertSee('Nombre corregido');
        $this->get(route('presolicitudes.imprimir',[$pre,'original'=>1]))->assertOk()->assertSee('Persona de prueba');
        $this->putJson(route('presolicitudes.update',$pre),$datos)->assertConflict();
    }
    public function test_editor_no_accede_a_datos_personales(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $this->actingAs($this->usuario(2))->get(route('presolicitudes.index'))->assertForbidden();
    }
    public function test_cambios_de_configuracion_obligan_recalcular_antes_de_enviar(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $datos=$this->envio();
        ProductoSimulador::where('pro_codigo','REG_GAR')->update(['pro_monto_maximo'=>100]);
        $this->postJson(route('prestamos.enviar'),$datos)->assertConflict();
        $this->assertSame(0,PresolicitudPrestamo::count());
    }
    public function test_vistas_publicas_y_configuracion_compilan(): void
    {
        $productos=ProductoSimulador::all();
        $html=view('publico._simulador',['productosSimulador'=>$productos])->render();
        $this->assertStringContainsString('Tus condiciones',$html);
        $this->actingAs($this->usuario(1))->get(route('simulador-prestamos.index'))->assertOk()->assertSee('Reglamento y supuestos');
    }
    public function test_impresion_publica_requiere_la_sesion_de_origen(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $envio=$this->envio();
        $r=$this->postJson(route('prestamos.enviar'),$envio)->assertCreated();
        $url=$r->json('imprimir_url');
        $this->get($url)->assertOk()->assertSee('Persona de prueba');
        $this->withSession(['prestamo_actual'=>null])->get($url)->assertNotFound();
    }
    public function test_correccion_financiera_preserva_original_y_registra_inicio_del_plazo(): void
    {
        $this->markTestSkipped("Módulo de presolicitudes archivado");
        $this->postJson(route('prestamos.enviar'),$this->envio())->assertCreated();
        $pre=PresolicitudPrestamo::first();$this->actingAs($this->usuario(4));
        $this->put(route('presolicitudes.update',$pre),['version'=>1,'estado'=>'LISTA_PARA_ATENCION','formulario'=>$pre->pre_formulario,
            'recalcular'=>1,'simulacion'=>['monto_solicitado'=>40000],'documentacion_completa'=>1])->assertRedirect();
        $pre->refresh();
        $this->assertEquals(40000,$pre->pre_simulacion['monto_solicitado']);
        $this->assertEquals(50000,$pre->pre_original['simulacion']['monto_solicitado']);
        $this->assertNotNull($pre->pre_documentacion_validada_en);
        $this->assertEquals(40000,$pre->revisiones->first()->rev_datos['simulacion']['monto_solicitado']);
    }
    public function test_configuracion_valida_guarda_reglas_e_historial(): void
    {
        $this->actingAs($this->usuario(1));$p=ProductoSimulador::where('pro_codigo','REG_GAR')->first();
        $datos=['pro_nombre'=>'Regular con Garantes','pro_monto_minimo'=>1,'pro_monto_maximo'=>245000,'pro_moneda'=>'BOB',
            'pro_plazo_minimo_meses'=>1,'pro_plazo_maximo_meses'=>180,'pro_activo'=>1,'pro_requiere_garantes'=>1,'pro_cantidad_garantes'=>2,
            'par_tasa'=>11.99,'par_tipo_tasa'=>'ANUAL_NOMINAL','par_metodo_amortizacion'=>'FRANCES','par_base_interes'=>'SALDO','par_frecuencia_pago'=>'MENSUAL','par_estado'=>'INFERIDO',
            'reg_estado'=>'PENDIENTE','politica'=>LoanPolicy::defaults('REG_GAR'),'escalas_json'=>'[]'];
        $this->putJson(route('simulador-prestamos.update',$p),array_replace($datos,['pro_plazo_minimo_meses'=>181]))->assertUnprocessable();
        $this->putJson(route('simulador-prestamos.update',$p),array_replace($datos,['escalas_json'=>'[[1,60,25000],[60,120,50000]]']))->assertUnprocessable();
        $this->put(route('simulador-prestamos.update',$p),$datos)->assertRedirect();
        $p->refresh();$this->assertEquals(.75,$p->pro_reglas_simulacion['porcentaje_referencial']);
        $this->assertGreaterThan(0,$p->historial()->count());
        $this->assertSame('COMPATIBLE',$this->evaluar($this->entrada())['estado']);
    }
    public function test_iniciacion_y_bonos_temporales_de_emergencia(): void
    {
        $s=$this->evaluar($this->entrada(['producto'=>'INIC','monto_solicitado'=>28000,'plazo'=>30,'aportes'=>1,'recien_egresado'=>1]));
        $this->assertSame('COMPATIBLE',$s['estado']);
        $this->assertEquals(27930,$s['neto_estimado']);
        $e=$this->evaluar($this->entrada(['producto'=>'EMERG','monto_solicitado'=>10000,'plazo'=>60,'aportes'=>61,'aportes_continuos'=>1,'emergencias_mes'=>0,'liquido_pagable'=>4000,'bonos_temporales'=>1000]));
        $this->assertEquals(2175,$e['cuota_maxima_estimada']);
        $e=$this->evaluar($this->entrada(['producto'=>'EMERG','monto_solicitado'=>300,'plazo'=>1,'aportes'=>6,'aportes_continuos'=>1,'emergencias_mes'=>2]));
        $this->assertSame('INCUMPLE_REQUISITO',$e['estado']);
    }
}
