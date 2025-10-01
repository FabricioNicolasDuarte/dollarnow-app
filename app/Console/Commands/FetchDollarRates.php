<?php

namespace App\Console\Commands;

use App\Models\DollarRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchDollarRates extends Command
{
    // Firma para llamar al comando desde la terminal
    protected $signature = 'app:fetch-dollar-rates';

    // Descripción del comando
    protected $description = 'Fetch the latest dollar exchange rates from DolarAPI';

    public function handle()
    {
        $this->info('Fetching latest dollar rates...');

        try {
            // Hacemos la petición GET a la API externa
            $response = Http::get('https://dolarapi.com/v1/dolares');

            if ($response->successful()) {
                $rates = $response->json();
                $savedCount = 0;

                foreach ($rates as $rate) {
                    // Saltamos registros incompletos que no nos sirven
                    if (!isset($rate['casa']) || is_null($rate['compra']) || is_null($rate['venta']) || !isset($rate['fechaActualizacion'])) {
                        continue;
                    }

                    // Usamos updateOrCreate para insertar o actualizar, evitando duplicados
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
                    $savedCount++;
                }

                $this->info("Successfully fetched and saved/updated {$savedCount} rates.");
            } else {
                $this->error('Failed to fetch rates from API.');
                Log::error('DolarAPI request failed', ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('Exception during DolarAPI fetch', ['exception' => $e->getMessage()]);
        }

        return 0;
    }
}