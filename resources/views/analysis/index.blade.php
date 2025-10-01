<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Análisis de Promedios Mensuales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Formulario de Selección -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="GET" action="{{ route('analysis.index') }}" class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Seleccionar Criterios
                        </h3>
                        <div>
                            <x-input-label for="type" :value="__('Tipo de Dólar')" />
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Elige un tipo --</option>
                                @foreach ($dollarTypes as $type)
                                    <option value="{{ $type }}" @selected(isset($oldInput['type']) && $oldInput['type'] == $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="value_type" :value="__('Valor a Promediar')" />
                            <select id="value_type" name="value_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Elige un valor --</option>
                                <option value="buy_price" @selected(isset($oldInput['value_type']) && $oldInput['value_type'] == 'buy_price')>Compra</option>
                                <option value="sell_price" @selected(isset($oldInput['value_type']) && $oldInput['value_type'] == 'sell_price')>Venta</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Calcular Promedio') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Resultados -->
            @if ($monthlyAverages)
            <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Resultados para: {{ ucfirst($oldInput['type']) }} - Valor de {{ $oldInput['value_type'] == 'buy_price' ? 'Compra' : 'Venta' }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Año</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mes</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($monthlyAverages as $average)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $average->year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::create()->month($average->month)->format('F') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-300">$ {{ number_format($average->average, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron resultados para esta combinación.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <!-- AÑADE ESTE FORMULARIO DESPUÉS DE LA TABLA -->
                     <div class="mt-6 flex justify-end">
                        <form method="POST" action="{{ route('history.store') }}">
                            @csrf
                            <input type="hidden" name="type" value="{{ $oldInput['type'] }}">
                            <input type="hidden" name="value_type" value="{{ $oldInput['value_type'] }}">
                            <x-secondary-button type="submit">
                                {{ __('Guardar Consulta') }}
                            </x-secondary-button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>