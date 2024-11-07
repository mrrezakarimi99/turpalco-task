<?php

namespace App\Service\Infrastructure;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class ToursInterface
{
    protected $baseUrl;

    abstract public function generateHeaders($data): array;

    abstract public function getTours(): Collection;

    abstract public function getTour($id): array;

    abstract public function searchTours($data): Collection;

    abstract public function availableTour($data): bool;

    public function getAbsoluteUrl($path): string
    {
        return $this->baseUrl . $path;
    }

    public function sendRequest($path, $method, $headers = [], $data = []): array
    {
        if (!in_array($method, ['get', 'post', 'put', 'delete'])) {
            return [
                'status'  => false,
                'message' => 'Invalid method',
            ];
        }
        try {
            $response = Http::withHeaders($this->generateHeaders($headers))
                ->$method($this->getAbsoluteUrl($path), $data);
            if ($response->failed()) {
                Log::error('Failed to send request to the API', [
                    'response' => $response->json(),
                ]);
                return [
                    'status'   => false,
                    'message'  => 'Failed to send request to the API',
                    'response' => $response->json(),
                ];
            }
            return [
                'status'   => true,
                'message'  => 'Request sent successfully',
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send request to the API', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);
            return [
                'status'  => false,
                'message' => 'Failed to send request to the API',
            ];
        }


    }
}
