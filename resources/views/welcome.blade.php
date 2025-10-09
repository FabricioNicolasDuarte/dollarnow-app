<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dollar Now</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #ffffff;
            color: #fff;
            margin: 0;
            overflow: hidden; /* Evita barras de scroll */
        }

        /* Video de Fondo */
        #background-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            object-fit: cover; /* Asegura que el video cubra todo el espacio */
        }

        /* Capa semi-transparente sobre el video */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Capa oscura. Puedes ajustar la opacidad (el último valor) para hacerlo más claro u oscuro. */
            /* Lo he bajado a 0.3 para que el video se vea mejor. */
            background-color: rgba(0, 0, 0, 0.3);
            z-index: -99;
        }

        /* Contenedor principal para centrar el contenido */
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Contenido central (logo, mensaje y botones) */
        .content {
            z-index: 1;
            padding: 20px;
        }

        .logo-container {
             margin-bottom: 2rem; /* 32px */
        }

        .logo-container img {
            height: 6rem; /* 96px */
            width: auto;
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-message {
            font-size: 1.5rem; /* 24px */
            font-weight: 600;
            margin-bottom: 2rem; /* 32px */
            max-width: 600px;
            line-height: 1.5;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        /* Contenedor para los botones */
        .button-container {
            display: flex;
            gap: 1rem; /* 16px */
            justify-content: center;
        }

        /* Estilo base para los botones */
        .welcome-button {
            display: inline-block;
            padding: 0.75rem 1.5rem; /* 12px 24px */
            border-radius: 0.5rem; /* 8px */
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            border: 2px solid transparent;
            cursor: pointer;
        }

        /* Botón primario (verde) */
        .btn-primary {
            background-color: #84cc16; /* Verde lima */
            color: #1a202c;
        }
        .btn-primary:hover {
            background-color: #a3e635;
        }

        /* Botón secundario (transparente con borde) */
        .btn-secondary {
            background-color: transparent;
            color: #fff;
            border-color: #fff;
        }
        .btn-secondary:hover {
            background-color: #fff;
            color: #1a202c;
        }
    </style>
</head>
<body class="antialiased">
    <video autoplay muted loop id="background-video">
        <source src="{{ asset('videos/videofondoinicio.mp4') }}" type="video/mp4">
        Tu navegador no soporta el tag de video.
    </video>

    <!-- Capa de superposición -->
    <div class="video-overlay"></div>

    <div class="main-container">
        <div class="content">

            <!-- Mensaje de bienvenida -->
            <h1 class="welcome-message">
                Tu App de Cotizaciones del Dólar en Tiempo Real.
            </h1>

            <!-- Contenedor de botones condicionales -->
            <div class="button-container">
                @auth
                    {{-- Si el usuario está logueado, muestra el botón de Dashboard --}}
                    <a href="{{ url('/dashboard') }}" class="welcome-button btn-primary">Dashboard</a>
                @else
                    {{-- Si el usuario no está logueado, muestra Login y Register --}}
                    <a href="{{ route('login') }}" class="welcome-button btn-secondary">Login</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="welcome-button btn-primary">Registrarse</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
