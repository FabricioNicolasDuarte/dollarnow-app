<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cotizaciones del Dólar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Calculadora Interactiva Bidireccional -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Calculadora de Conversión
                    </h3>
                    <div class="flex items-center justify-center space-x-4 mb-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="conversion_mode" value="ars_to_usd" class="form-radio text-lime-500 focus:ring-lime-500" checked>
                            <span class="text-gray-700 dark:text-gray-300">Pesos a Dólares</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="conversion_mode" value="usd_to_ars" class="form-radio text-lime-500 focus:ring-lime-500">
                            <span class="text-gray-700 dark:text-gray-300">Dólares a Pesos</span>
                        </label>
                    </div>
                    <div>
                        <x-input-label id="input_label" for="amount_input" :value="__('Ingrese monto en Pesos Argentinos (ARS)')" />
                        <input id="amount_input" type="number" placeholder="Ej: 15000" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md shadow-sm">
                    </div>
                    <div id="calculator_results" class="mt-4 space-y-2 text-gray-900 dark:text-gray-100">
                        <!-- Resultados de la calculadora -->
                    </div>
                </div>
            </div>

            <!-- Tabla de Cotizaciones -->
            <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Historial de Cotizaciones del Dólar
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700 bg-opacity-80 dark:bg-opacity-80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Compra</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Venta</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @if (!empty($rates))
                                    @foreach ($rates as $rate)
                                        <tr class="bg-opacity-80 dark:bg-opacity-80">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($rate['fecha'])->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $rate['nombre'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $rate['tipo'] }}</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-300">$ {{ number_format($rate['compra'], 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-300">$ {{ number_format($rate['venta'], 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">No se pudieron obtener las cotizaciones en este momento. Por favor, intente más tarde.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Nueva Sección: Fuente de Datos -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 shadow sm:rounded-lg text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Los datos de las cotizaciones son obtenidos a través de la API pública de
                    <a href="https://dolarapi.com/" target="_blank" rel="noopener noreferrer" class="font-semibold text-lime-600 dark:text-lime-400 hover:underline">
                        dolarapi.com
                    </a>.
                </p>
            </div>

        </div>
    </div>

    {{-- Script para la lógica de la calculadora bidireccional --}}
    <script>
        const ratesData = {!! json_encode($rates) !!};
        const amountInput = document.getElementById('amount_input');
        const resultsContainer = document.getElementById('calculator_results');
        const conversionModeRadios = document.querySelectorAll('input[name="conversion_mode"]');
        const inputLabel = document.getElementById('input_label');
        let currentMode = 'ars_to_usd';
        const arsFormatter = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' });
        const usdFormatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

        function calculate() {
            const amount = parseFloat(amountInput.value);
            resultsContainer.innerHTML = '';
            if (isNaN(amount) || amount <= 0) return;

            if (currentMode === 'ars_to_usd') {
                ratesData.forEach(rate => {
                    const sellPrice = parseFloat(rate.venta);
                    if (sellPrice > 0) {
                        appendResult(rate.nombre, usdFormatter.format(amount / sellPrice));
                    }
                });
            } else {
                ratesData.forEach(rate => {
                    const buyPrice = parseFloat(rate.compra);
                    if (buyPrice > 0) {
                        appendResult(rate.nombre, arsFormatter.format(amount * buyPrice));
                    }
                });
            }
        }

        function appendResult(name, value) {
            const resultElement = document.createElement('div');
            resultElement.className = 'flex justify-between items-center border-b border-gray-200 dark:border-gray-700 py-1';
            resultElement.innerHTML = `<span class="font-medium">${name}</span><span class="font-bold text-lime-500 dark:text-lime-400">${value}</span>`;
            resultsContainer.appendChild(resultElement);
        }

        function updateMode() {
            currentMode = document.querySelector('input[name="conversion_mode"]:checked').value;
            amountInput.value = '';
            resultsContainer.innerHTML = '';
            inputLabel.textContent = currentMode === 'ars_to_usd' ? 'Ingrese monto en Pesos Argentinos (ARS)' : 'Ingrese monto en Dólares (USD)';
            amountInput.placeholder = currentMode === 'ars_to_usd' ? 'Ej: 150000' : 'Ej: 100';
        }

        amountInput.addEventListener('input', calculate);
        conversionModeRadios.forEach(radio => radio.addEventListener('change', updateMode));
        updateMode();
    </script>
</x-app-layout>

