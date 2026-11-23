<?php
namespace App\Http\Controllers;

use App\Models\ProductoSimulador;
use App\Services\Prestamos\LoanPolicy;
use App\Services\Prestamos\LoanSimulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SimuladorPrestamoController extends Controller
{
    public function simular(Request $request, LoanSimulationService $servicio)
    {
        $datos=$request->validate(LoanPolicy::inputRules());
        $producto=ProductoSimulador::with(['parametro','reglaCapacidad'])->where('pro_codigo',$datos['producto'])->firstOrFail();
        $resultado=$servicio->simular($producto,$datos); $id=(string)Str::uuid();
        $request->session()->put('prestamo_actual',['id'=>$id,'vence'=>now()->addMinutes(30)->timestamp,'resultado'=>$resultado]);
        return response()->json($resultado+['simulacion_id'=>$id,'imprimir_url'=>route('prestamos.plan',$id)])->header('Cache-Control','no-store');
    }
    public function plan(Request $request,string $simulacion)
    {
        $actual=$request->session()->get('prestamo_actual');
        abort_unless($actual && hash_equals($actual['id'],$simulacion) && $actual['vence']>=now()->timestamp && isset($actual['resultado']['plan']),404);
        return response()->view('prestamos.imprimir',['simulacion'=>$actual['resultado'],'formulario'=>null,'referencia'=>null])->header('Cache-Control','private, no-store');
    }
}
