<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Aportes - ASCINALSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d1420;
            color: #fff;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .login-bg {
            position: fixed;
            inset: 0;
            background-image: url('{{ asset('img/hero-ascinalss.png') }}');
            background-size: cover;
            background-position: center;
            z-index: 0;
            filter: blur(2px);
            transform: scale(1.05);
        }

        .login-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(13,20,32,0.88) 0%, rgba(20,30,50,0.92) 100%);
            z-index: 1;
        }

        .wrap {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(201, 161, 90, 0.25);
            border-radius: 20px;
            padding: 36px 32px;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-img {
            max-height: 70px;
            margin-bottom: 14px;
            filter: drop-shadow(0 4px 12px rgba(201, 161, 90, 0.3));
        }

        .badge-inst {
            display: inline-block;
            background: rgba(201, 161, 90, 0.15);
            border: 1px solid rgba(201, 161, 90, 0.4);
            color: #c9a15a;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 50px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: #8fa0be;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .5px;
            color: #c9a15a;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-box i {
            position: absolute;
            left: 16px;
            color: #8fa0be;
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 13px 16px 13px 44px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: all .25s ease;
        }

        .form-control:focus {
            border-color: #c9a15a;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(201, 161, 90, 0.2);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #c9a15a 0%, #a07830 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            color: #0d1420;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all .3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(201, 161, 90, 0.4);
            background: linear-gradient(135deg, #d8b26b 0%, #b3883a 100%);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #8fa0be;
            font-size: 12px;
            text-decoration: none;
            transition: color .2s;
        }

        .back-link a:hover {
            color: #c9a15a;
        }
    </style>
</head>
<body>
    <div class="login-bg"></div>
    <div class="login-overlay"></div>

    <div class="wrap">
        <div class="card">
            <div class="header">
                <img src="{{ asset('img/logo.png') }}" alt="ASCINALSS" class="logo-img">
                <div><span class="badge-inst">Portal del Afiliado</span></div>
                <h1 class="title">Consulta de Aportes</h1>
                <p class="subtitle">Ingrese sus credenciales militares para ver su estado de cuenta</p>
            </div>

            @if(session('status'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('status') }}
                </div>
            @endif

            @if(isset($errors) && $errors->has('login'))
                <div class="alert-error">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-top: 2px;"></i>
                    <div>{{ $errors->first('login') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('aportes.autenticar') }}" id="formAportes">
                @csrf
                <div style="display:none !important;" aria-hidden="true"><input type="text" name="telefono_confirmacion" tabindex="-1" autocomplete="off"></div>
                <div class="form-group">
                    <label class="form-label">Cédula de Identidad (CI)</label>
                    <div class="input-box">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" name="ci" id="inputCi" class="form-control" placeholder="Ej: 4892110" value="{{ old('ci') }}" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Número de Papeleta / Matrícula</label>
                    <div class="input-box">
                        <i class="fa-solid fa-receipt"></i>
                        <input type="text" name="numero_papeleta" id="inputPapeleta" class="form-control" placeholder="Ej: 10452" value="{{ old('numero_papeleta') }}" required autocomplete="off">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Consultar Aportes</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="back-link">
                <a href="{{ route('publico.inicio') }}"><i class="fa-solid fa-arrow-left"></i> Volver a la página principal</a>
            </div>
        </div>
    </div>


</body>
</html>
