<?php
namespace App\Http\Controllers;

use App\Models\PresolicitudPrestamo;
use App\Models\ProductoSimulador;
use App\Models\RevisionPresolicitud;
use App\Services\Prestamos\LoanPolicy;
use App\Services\Prestamos\LoanSimulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PresolicitudPrestamoController extends Controller
{
    public function store(Request $request, LoanSimulationService $servicio)
    {
        abort_if(!empty($request->input('empresa_verificacion')), 422, 'Petición inválida detectada.');
        $actual=$request->session()->get('prestamo_actual');
        abort_unless($actual && hash_equals($actual['id'],(string)$request->input('simulacion_id')) && $actual['vence']>=now()->timestamp,419,'La estimación venció. Vuelve a calcular.');
        $snapshot=$actual['resultado'];
        abort_unless($snapshot['puede_enviar']??false,422,'Solo se reciben presolicitudes que cumplan las condiciones evaluadas.');
        $datos=$request->validate(LoanPolicy::formularioRules($snapshot['producto']==='REG_GAR')+[
            'acepta_condiciones'=>'required|accepted','simulacion_id'=>'required|uuid',
        ]);
        $existente=PresolicitudPrestamo::where('pre_simulacion_id',$actual['id'])->first();
        if ($existente) return response()->json(['referencia'=>$existente->pre_referencia,'mensaje'=>'Esta presolicitud ya fue recibida.','imprimir_url'=>route('prestamos.formulario',$actual['id'])])->header('Cache-Control','no-store');
        $producto=ProductoSimulador::with(['parametro','reglaCapacidad'])->where('pro_codigo',$snapshot['producto'])->firstOrFail();
        $vigente=$servicio->simular($producto,$snapshot['datos']);
        $a=$snapshot; $b=$vigente; unset($a['generado_en'],$b['generado_en']);
        abort_unless($a==$b,409,'Las condiciones cambiaron. Vuelve a calcular antes de enviar.');
        $formulario=$datos['formulario'];
        if ($snapshot['producto']!=='REG_GAR') unset($formulario['garantes']);
        $pre=PresolicitudPrestamo::firstOrCreate(['pre_simulacion_id'=>$actual['id']],[
            'pre_referencia'=>(string)Str::uuid(),'pre_formulario'=>$formulario,'pre_simulacion'=>$snapshot,
            'pre_original'=>['formulario'=>$formulario,'simulacion'=>$snapshot],
            'pre_condiciones'=>LoanPolicy::CONDICIONES_VERSION."\n".LoanPolicy::CONDICIONES,'pre_aceptado_en'=>now(),
        ]);
        return response()->json(['referencia'=>$pre->pre_referencia,'mensaje'=>'Recibido para revisión. No constituye aprobación de préstamo. Conserva esta referencia para tu atención en oficina.','condiciones'=>LoanPolicy::CONDICIONES,'imprimir_url'=>route('prestamos.formulario',$actual['id'])],201)->header('Cache-Control','no-store');
    }
    public function formulario(Request $request,string $simulacion)
    {
        $actual=$request->session()->get('prestamo_actual');
        abort_unless($actual && hash_equals($actual['id'],$simulacion) && $actual['vence']>=now()->timestamp,404);
        $pre=PresolicitudPrestamo::where('pre_simulacion_id',$simulacion)->firstOrFail();
        return response()->view('prestamos.imprimir',$pre->pre_original+['referencia'=>$pre->pre_referencia])->header('Cache-Control','private, no-store');
    }
    public function index(Request $request)
    {
        $filtros=$request->validate(['estado'=>'nullable|in:'.implode(',',PresolicitudPrestamo::ESTADOS),'referencia'=>'nullable|string|max:36']);
        $presolicitudes=PresolicitudPrestamo::when($filtros['estado']??null,fn($q,$v)=>$q->where('pre_estado',$v))
            ->when($filtros['referencia']??null,fn($q,$v)=>$q->where('pre_referencia',$v))->latest('pre_id')->paginate(20)->withQueryString();
        return response()->view('admin.presolicitudes.index',compact('presolicitudes'))->header('Cache-Control','private, no-store');
    }
    public function show(PresolicitudPrestamo $presolicitud)
    {
        $presolicitud->load('revisiones.usuario');
        return response()->view('admin.presolicitudes.show',compact('presolicitud'))->header('Cache-Control','private, no-store');
    }
    public function update(Request $request, PresolicitudPrestamo $presolicitud, LoanSimulationService $servicio)
    {
        $datos=$request->validate(LoanPolicy::formularioRules($presolicitud->pre_simulacion['producto']==='REG_GAR')+[
            'version'=>'required|integer|min:1','estado'=>'required|in:'.implode(',',PresolicitudPrestamo::ESTADOS),
            'observaciones'=>'nullable|string|max:5000','documentacion_completa'=>'nullable|boolean',
            'recalcular'=>'nullable|boolean','simulacion'=>'nullable|array',
        ]);
        DB::transaction(function() use($request,$presolicitud,$servicio,$datos) {
            $pre=PresolicitudPrestamo::whereKey($presolicitud->pre_id)->lockForUpdate()->firstOrFail();
            abort_unless($pre->pre_version==(int)$datos['version'],409,'Otro evaluador modificó esta presolicitud. Recarga la página.');
            $snapshot=$pre->pre_simulacion;
            if ($request->boolean('recalcular')) {
                $entrada=array_replace($snapshot['datos'],$request->input('simulacion',[]));
                $entrada=Validator::make($entrada,LoanPolicy::inputRules())->validate();
                $producto=ProductoSimulador::with(['parametro','reglaCapacidad'])->where('pro_codigo',$entrada['producto'])->firstOrFail();
                $snapshot=$servicio->simular($producto,$entrada);
                abort_unless(isset($snapshot['plan']),422,'No hay parámetros vigentes para recalcular.');
            }
            $formulario=$datos['formulario'];
            if ($snapshot['producto']!=='REG_GAR') unset($formulario['garantes']);
            $pre->fill(['pre_formulario'=>$formulario,'pre_simulacion'=>$snapshot,'pre_estado'=>$datos['estado'],
                'pre_observaciones'=>$datos['observaciones']??null,'pre_version'=>$pre->pre_version+1]);
            if ($request->boolean('documentacion_completa') && !$pre->pre_documentacion_validada_en) $pre->pre_documentacion_validada_en=now();
            $pre->save();
            RevisionPresolicitud::create(['rev_presolicitud_id'=>$pre->pre_id,'rev_usuario_id'=>$request->user()->usu_id,
                'rev_version'=>$pre->pre_version,'rev_datos'=>['formulario'=>$formulario,'simulacion'=>$snapshot,
                    'estado'=>$pre->pre_estado,'observaciones'=>$pre->pre_observaciones,'documentacion_validada_en'=>$pre->pre_documentacion_validada_en?->toIso8601String()]]);
        });
        return back()->with('exito','Revisión guardada. No constituye aprobación crediticia.');
    }
    public function imprimir(Request $request,PresolicitudPrestamo $presolicitud)
    {
        $request->validate(['original'=>'nullable|boolean','version'=>'nullable|integer|min:2']);
        $datos=['formulario'=>$presolicitud->pre_formulario,'simulacion'=>$presolicitud->pre_simulacion];
        if ($request->boolean('original')) $datos=$presolicitud->pre_original;
        elseif ($request->filled('version')) $datos=$presolicitud->revisiones()->where('rev_version',$request->integer('version'))->firstOrFail()->rev_datos;
        return response()->view('prestamos.imprimir',$datos+['referencia'=>$presolicitud->pre_referencia])->header('Cache-Control','private, no-store');
    }
}
