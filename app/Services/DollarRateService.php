<?php

namespace App\Services;

use App\Models\DollarRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DollarRateService
{
    /**
     * Llama a la API, guarda/actualiza los datos en la base de datos,
     * y devuelve las cotizaciones procesadas.
     *
     * @return array
     */
    public function fetchAndStoreRates(): array
    {
        try {
            $response = Http::get('https://dolarapi.com/v1/dolares');

            if ($response->failed()) {
                Log::error('DolarAPI request failed', ['status' => $response->status()]);
                return [];
            }

            $rates = $response->json();
            $processedRates = [];

            foreach ($rates as $rate) {
                if (!isset($rate['casa']) || is_null($rate['compra']) || is_null($rate['venta']) || !isset($rate['fechaActualizacion'])) {
                    continue;
                }

                // Usamos updateOrCreate para guardar los datos en la base de datos.
                // Esto es lo que hacía tu antiguo comando.
                DollarRate::updateOrCreate(
                    [
                        'type' => $rate['casa'],
                        'rate_date' => now()->parse($rate['fechaActualizacion'])->toDateString(),
                    ],
                    [
                        'name' => $rate['nombre'],
                        'buy_price' => $rate['compra'],
                        'sell_price' => $rate['venta']
                    ]
                );

                // También preparamos el array para devolverlo, así la vista lo puede usar inmediatamente.
                $processedRates[] = [
                    'nombre' => $rate['nombre'],
                    'tipo'   => $rate['casa'],
                    'compra' => $rate['compra'],
                    'venta'  => $rate['venta'],
                    'fecha'  => $rate['fechaActualizacion'],
                ];
            }

            return $processedRates;

        } catch (\Exception $e) {
            Log::error('Exception during DolarAPI fetch', ['exception' => $e->getMessage()]);
            return [];
        }
    }
}

