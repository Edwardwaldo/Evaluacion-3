<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UfService
{
    protected string $endpoint = 'https://mindicador.cl/api/uf';

    public function obtenerValorUf(): array
    {
        try {
            $response = Http::timeout(3)->get($this->endpoint);

            if ($response->successful()) {
                $serie = $response->json('serie.0');

                if ($serie) {
                    return [
                        'valor' => $serie['valor'],
                        'fecha' => $serie['fecha'],
                        'origen' => 'API externa (mindicador.cl)',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Sin conexión o servicio caído: seguimos abajo con el valor simulado.
        }

        return $this->valorSimulado();
    }

    protected function valorSimulado(): array
    {
        return [
            'valor' => round(38000 + (mt_rand(-1500, 1500) / 10), 2),
            'fecha' => now()->toDateString(),
            'origen' => 'Simulación local (servicio externo no disponible)',
        ];
    }
}
