<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al sistema - ASCINALSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d1420;
            overflow: hidden;
        }

        /* Fondo con la misma imagen del hero público */
        .login-bg {
            position: fixed;
            inset: 0;
            background-image: url('{{ asset('img/hero-ascinalss.png') }}');
            background-size: cover;
            background-position: center top;
            z-index: 0;
            transform: scale(1.05); /* pequeño zoom para evitar bordes blancos */
        }

        .login-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(13,20,32,0.82) 0%, rgba(26,35,56,0.88) 100%);
            z-index: 1;
        }

        /* Partículas decorativas (las mismas del hero público) */
        .login-particles {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }
        .particle {
            position: absolute;
            width: 3px; height: 3px;
            background: #c9a15a;
            border-radius: 50%;
        }

        /* Contenedor centrado */
        .login-wrap {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 420px;
            padding: 24px;
        }

        /* Card glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 44px 40px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4);
        }

        /* Logo centrado */
        .login-logo {
            display: block;
            margin: 0 auto 28px;
            max-height: 72px;
            width: 100%;
        }

        .login-eyebrow {
            text-align: center;
            font-size: 11px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #c9a15a;
            margin-bottom: 6px;
        }

        .login-titulo {
            text-align: center;
            font-size: 17px;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
            margin-bottom: 32px;
        }

        /* Alerta de error */
        .login-error {
            background: rgba(255, 80, 80, 0.12);
            border: 1px solid rgba(255, 80, 80, 0.3);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #ff9a9a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Campos del formulario */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            margin-bottom: 8px;
        }

        .form-input-wrap {
            position: relative;
        }

        .form-input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: rgba(255,255,255,0.3);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            padding: 13px 14px 13px 40px;
            font-size: 14px;
            color: #fff;
            outline: none;
            transition: border-color 0.3s ease, background 0.3s ease;
            font-family: inherit;
        }

        .form-input::placeholder { color: rgba(255,255,255,0.25); }

        .form-input:focus {
            border-color: rgba(201,161,90,0.55);
            background: rgba(255,255,255,0.1);
        }

        /* Botón de ingreso */
        .login-btn {
            width: 100%;
            margin-top: 8px;
            background: #c9a15a;
            color: #241a06;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: .5px;
            transition: background 0.25s ease, transform 0.2s ease, box-shadow 0.25s ease;
        }

        .login-btn:hover {
            background: #e0c088;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(201,161,90,0.35);
        }

        .login-btn:active { transform: translateY(0); }

        /* Link volver al sitio */
        .login-volver {
            display: block;
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: color 0.2s;
        }
        .login-volver:hover { color: rgba(255,255,255,0.7); }
        .login-volver i { margin-right: 4px; }

        @media (max-width: 480px) {
            .login-card { padding: 36px 28px; }
        }
    </style>
</head>
<body>

<div class="login-bg"></div>
<div class="login-overlay"></div>
<div class="login-particles" id="loginParticles"></div>

<div class="login-wrap">
    <div class="login-card">
        <img src="{{ asset('img/logo-top-menu.png') }}" alt="ASCINALSS" class="login-logo">

        <p class="login-eyebrow">Panel Administrativo</p>
        <p class="login-titulo">Acceso al sistema</p>

        @if($errors->any())
            <div class="login-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                Usuario o contraseña incorrectos.
            </div>
        @endif

        <form method="POST" action="{{ url('/auth') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="uuo">Usuario</label>
                <div class="form-input-wrap">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="uuo" id="uuo" class="form-input"
                           placeholder="Nombre de usuario" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="ovc">Contraseña</label>
                <div class="form-input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="ovc" id="ovc" class="form-input"
                           placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="login-btn">
                Ingresar <i class="fa-solid fa-arrow-right-to-bracket" style="margin-left:6px;"></i>
            </button>
        </form>

        <a href="{{ route('publico.inicio') }}" class="login-volver">
            <i class="fa-solid fa-arrow-left"></i> Volver al sitio
        </a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
    // Partículas doradas (mismas del hero público)
    var container = document.getElementById('loginParticles');
    for (var i = 0; i < 28; i++) {
        var p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.opacity = (Math.random() * 0.4 + 0.1).toFixed(2);
        container.appendChild(p);
        gsap.to(p, {
            y: (Math.random() * 40 - 20),
            x: (Math.random() * 40 - 20),
            duration: 3 + Math.random() * 4,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut'
        });
    }

    // Animación de entrada de la card
    gsap.from('.login-card', {
        opacity: 0,
        y: 24,
        scale: 0.97,
        duration: 0.7,
        ease: 'power2.out',
        delay: 0.1
    });

    gsap.from('.login-logo', {
        opacity: 0,
        scale: 0.85,
        duration: 0.8,
        ease: 'back.out(1.4)',
        delay: 0.3
    });
</script>
</body>
</html>