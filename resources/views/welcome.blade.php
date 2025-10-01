<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dollar Now</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

    <div class="relative min-h-screen">
    
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover -z-10">
            <source src="https://res.cloudinary.com/dx5end206/video/upload/v1759326802/videoiniciodollarnow.mp4" type="video/mp4">
            Tu navegador no soporta el tag de video.
        </video>
        
        <div class="absolute inset-0 bg-black/60 -z-10"></div>

        <div class="relative min-h-screen flex flex-col items-center justify-center">

            @if (Route::has('login'))
                <nav class="absolute top-0 right-0 p-6">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-md px-3 py-2 text-white text-lg ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-md px-3 py-2 text-white text-lg ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="rounded-md px-3 py-2 text-white text-lg ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] ms-4"
                            >
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

            <main class="text-center">
                {{-- El contenido del título y el párrafo ha sido eliminado. --}}
            </main>

        </div>
    </div>

</body>
</html>