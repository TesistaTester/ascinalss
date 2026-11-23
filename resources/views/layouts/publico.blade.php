{{-- resources/views/layouts/publico.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('titulo', 'ASCINALSS')</title>
<meta name="description" content="Asociación Nacional de Suboficiales y Sargentos de las Fuerzas Armadas de la Nación">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
@include('publico._estilos')
</style>
@if(request()->routeIs('publico.inicio'))<link rel="stylesheet" href="{{ asset('css/prestamos.css') }}?v=100003">@endif
</head>
<body>
@yield('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
@include('publico._script')
</script>
@if(request()->routeIs('publico.inicio'))<script src="{{ asset('js/simulador-prestamos.js') }}?v=100003" defer></script>@endif
<script src="{{ asset('js/tours.js') }}?v=100000"></script>
@stack('scripts')
</body>
</html>
