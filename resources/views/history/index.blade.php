<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mi Historial de Consultas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                
                <div class="mb-4 bg-lime-100 border border-lime-400 text-lime-800 px-4 py-3 rounded relative dark:bg-lime-200 dark:border-lime-500" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($queries as $query)
                            <li class="py-4 flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-semibold">{{ ucfirst($query->type) }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Valor de {{ $query->value_type == 'buy_price' ? 'Compra' : 'Venta' }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('analysis.index', ['type' => $query->type, 'value_type' => $query->value_type]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Ver
                                    </a>
                                    <form method="POST" action="{{ route('history.destroy', $query) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button onclick="return confirm('¿Estás seguro de que quieres eliminar esta consulta?')">
                                            {{ __('Borrar') }}
                                        </x-danger-button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500 dark:text-gray-400">
                                No tienes consultas guardadas en tu historial.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>