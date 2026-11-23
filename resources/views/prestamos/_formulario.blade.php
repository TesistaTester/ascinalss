@php($f=$formulario ?? [])
<div class="simulador-grid">
@foreach(\App\Services\Prestamos\LoanPolicy::fields() as $key=>$label)
<label>{{ $label }}{{ in_array($key,['nombres','ci','telefono','objeto'])?' *':'' }}<input name="formulario[{{ $key }}]" value="{{ old('formulario.'.$key,$f[$key]??'') }}" type="{{ $key==='fecha_nacimiento'?'date':($key==='email'?'email':'text') }}" maxlength="255" @required(in_array($key,['nombres','ci','telefono','objeto']))></label>
@endforeach
</div>
<div data-garantes @if(!($conGarantes??false)) hidden @endif>
@for($i=0;$i<2;$i++)
<details><summary>Datos del garante {{ $i+1 }} (pueden completarse en oficina)</summary><div class="simulador-grid">
@foreach(\App\Services\Prestamos\LoanPolicy::garanteFields() as $key=>$label)
<label>{{ $label }}<input name="formulario[garantes][{{ $i }}][{{ $key }}]" value="{{ old('formulario.garantes.'.$i.'.'.$key,$f['garantes'][$i][$key]??'') }}" type="{{ $key==='fecha_nacimiento'?'date':($key==='email'?'email':'text') }}" maxlength="255"></label>
@endforeach
</div></details>
@endfor
</div>
