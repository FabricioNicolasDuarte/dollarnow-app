    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mi Historial de Consultas') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                {{-- Notificación de estado --}}
                @if (session('status'))
                    <div class="p-4 bg-lime-500/10 text-lime-500 border border-lime-500/20 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Lista de Consultas Guardadas -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 shadow sm:rounded-lg">
                    <div class="max-w-full">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Mis Búsquedas Guardadas
                        </h3>
                        <div class="space-y-4">
                            @forelse ($userQueries as $query)
                                <div class="flex justify-between items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $query->name }}</span>
                                    <div class="flex items-center gap-4">
                                        {{-- Botón para volver a ejecutar la búsqueda en la página de análisis --}}
                                        <a href="{{ route('analysis.index', ['type' => $query->type, 'value_type' => $query->value_type, 'period' => $query->period]) }}" class="text-sm text-lime-600 dark:text-lime-400 hover:underline">
                                            Ver Análisis
                                        </a>
                                        {{-- Formulario para eliminar la consulta --}}
                                        <form method="POST" action="{{ route('history.destroy', $query) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('¿Estás seguro de que quieres eliminar esta consulta?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No tienes ninguna consulta guardada. Realiza un análisis y guárdalo para verlo aquí.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Gráfico Comparativo de Consultas Guardadas --}}
                @if (!empty($chartData))
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Comparativa de Promedios Recientes
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Este gráfico compara el valor promedio del último mes disponible para cada una de tus consultas guardadas.
                        </p>
                        <div class="h-96">
                            <canvas id="historyChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (!empty($chartData))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('historyChart').getContext('2d');
                    const historyChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chartLabels) !!},
                            datasets: [{
                                label: 'Promedio del Último Mes ($)',
                                data: {!! json_encode($chartData) !!},
                                backgroundColor: 'rgba(132, 204, 22, 0.6)',
                                borderColor: 'rgba(132, 204, 22, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y', // Muestra las barras de forma horizontal para mejor legibilidad
                            scales: {
                                x: {
                                    beginAtZero: false,
                                    ticks: { color: '#94a3b8' },
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' }
                                },
                                y: {
                                    ticks: { color: '#94a3b8' },
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' }
                                }
                            },
                             plugins: {
                                legend: { display: false },
                                tooltip: {
                                    titleColor: '#e2e8f0',
                                    bodyColor: '#cbd5e1',
                                }
                            }
                        }
                    });
                });
            </script>
        @endif
    </x-app-layout>
