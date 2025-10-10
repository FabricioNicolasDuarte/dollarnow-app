<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="relative min-h-[90vh]">
        <!-- Background image y overlay -->
        <div class="absolute inset-0 z-0">
            <img src="/images/bg-dollar.png" alt="Background Dollar" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-transparent to-green-900/80"></div>
        </div>
        <!-- Contenido principal -->
        <div class="relative z-10 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gray-800 bg-opacity-60 rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
                    <!-- Panel lateral de bienvenida -->
                    <div class="md:w-1/3">
                        <div class="bg-gradient-to-b  from-black/80 via-black/80 to-green-800 bg-opacity-30 flex flex-col justify-center items-center py-10 px-6 h-full">
                            <img src="/images/icons/icon2.png" alt="Bienvenida" class="w-16 h-16 mb-4">
                            <h3 class="text-2xl font-bold text-white mb-2 text-center">Bienvenido al Centro de Control de tu Dólar</h3>
                            <p class="text-green-200 text-center mb-4">¡Qué bueno verte! Ya formas parte de la comunidad Dollar-Now.</p>
                        </div>
                    </div>
                    <!-- Panel principal de contenido -->
                    <div class="md:w-2/3 p-8 flex flex-col justify-center">
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold text-gray-100 mb-3 flex items-center gap-2">
                                <img src="/images/icons/icon3.png" alt="Ventana" class="w-6 h-6">
                                ¿Qué es Dollar-Now? Tu Ventana al Valor Real
                            </h4>
                            <p class="text-gray-300 mb-2">
                                Dollar-Now es la herramienta más confiable para monitorear y analizar al instante todas las cotizaciones importantes del dólar en tu país. Nos enfocamos en la especificidad para darte una visión completa, no solo un precio genérico.
                            </p>
                            <p class="text-gray-300 mb-2">
                                Hemos diseñado esta aplicación para ser tu aliado estratégico en el volátil mercado de divisas, asegurando que siempre tengas la información más rápida, precisa y relevante para tus finanzas y decisiones de negocio.
                            </p>
                        </div>
                        <!-- Panel de funcionalidades -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="flex gap-4 items-start bg-gray-900 bg-opacity-80 rounded-lg p-5 shadow hover:shadow-green-700 transition">
                                <img src="/images/icons/icon4.png" alt="Oficial" class="w-10 h-10 mt-1">
                                <div>
                                    <h5 class="font-bold text-green-400 mb-1">Dólar Oficial</h5>
                                    <p class="text-gray-400 text-sm">Mayorista y minorista. Referencia clara del mercado formal.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start bg-gray-900 bg-opacity-80 rounded-lg p-5 shadow hover:shadow-green-700 transition">
                                <img src="/images/icons/icon5.png" alt="Ahorro" class="w-10 h-10 mt-1">
                                <div>
                                    <h5 class="font-bold text-green-400 mb-1">Dólar Ahorro/Solidario</h5>
                                    <p class="text-gray-400 text-sm">Calculado con todos los impuestos y recargos aplicables, para que sepas el costo exacto por canales bancarios.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start bg-gray-900 bg-opacity-80 rounded-lg p-5 shadow hover:shadow-green-700 transition">
                                <img src="/images/icons/icon6.png" alt="Financieros" class="w-10 h-10 mt-1">
                                <div>
                                    <h5 class="font-bold text-green-400 mb-1">Dólares Financieros (MEP/CCL)</h5>
                                    <p class="text-gray-400 text-sm">Información crucial para inversores y empresas, con datos que se actualizan rápidamente.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start bg-gray-900 bg-opacity-80 rounded-lg p-5 shadow hover:shadow-green-700 transition">
                                <img src="/images/icons/icon7.png" alt="Blue" class="w-10 h-10 mt-1">
                                <div>
                                    <h5 class="font-bold text-green-400 mb-1">Dólar Blue/Informal</h5>
                                    <p class="text-gray-400 text-sm">El valor más buscado, presentado con transparencia y datos de fuentes confiables.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Panel de ventajas -->
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold text-gray-100 mb-3 flex items-center gap-2">
                                <img src="/images/icons/icon3.png" alt="Ventajas" class="w-6 h-6">
                                Una Experiencia Pensada en tu Decisión
                            </h4>
                            <p class="text-gray-300 mb-2">
                                Como usuario logueado, tienes acceso completo a funcionalidades clave que te dan una ventaja competitiva y tranquilidad financiera:
                            </p>
                            <ul class="space-y-4 pl-6 list-disc">
                                <li class="flex gap-3 items-center">
                                    <img src="/images/icons/icon9.png" alt="Históricos" class="w-6 h-6">
                                    <span class="text-white">
                                        Históricos y Gráficos Detallados: Accede a gráficos interactivos con el historial de cada cotización. Analiza tendencias, identifica patrones y toma decisiones basadas en datos sólidos.
                                    </span>
                                </li>
                                <li class="flex gap-3 items-center">
                                    <img src="/images/icons/icon10.png" alt="Calculadora" class="w-6 h-6">
                                    <span class="text-white">
                                        Calculadora Inteligente: Convierte de pesos a cualquier tipo de dólar (y viceversa) al instante, usando la cotización más actualizada.
                                    </span>
                                </li>
                                <li class="flex gap-3 items-center">
                                    <img src="/images/icons/icon11.png" alt="Fuentes" class="w-6 h-6">
                                    <span class="text-white">
                                        Fuentes de Datos Certificadas: Verificamos la información de las principales entidades financieras y mercados para asegurarte que los datos que ves son confiables y accionables.
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <!-- CTA final -->
                        <div class="text-center mt-8">
                            <span class="inline-block bg-green-700 bg-opacity-50 text-white font-bold px-6 py-3 rounded-lg shadow-lg text-base">
                                Dollar-Now no solo te muestra el precio, te ayuda a entender y a tomar el control. Explora y siente la confianza de tener el mercado cambiario al alcance de tu mano.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
