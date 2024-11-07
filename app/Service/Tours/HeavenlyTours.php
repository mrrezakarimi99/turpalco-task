<?php

namespace App\Service\Tours;

use App\Service\Infrastructure\ToursInterface;
use Illuminate\Support\Collection;

class HeavenlyTours extends ToursInterface
{
    public function __construct()
    {
        $this->baseUrl = config('services.heavenly_tours.base_url');
    }

    public function generateHeaders($data): array
    {
        return [];
    }

    public function getTours(): Collection
    {
        $response = $this->sendRequest('/tours','get');
        if ($response['status'] === false) {
            return collect([]);
        }
        return collect($response['response'])->map(function ($tour) {
            return [
                'id' => $tour['id'],
                'name' => $tour['title'],
                'excerpt' => $tour['excerpt'],
            ];
        });
    }

    public function getTour($id): array
    {
        $response = $this->sendRequest('/tours/' . $id,'get');
        if ($response['status'] === false) {
            return [];
        }
        return [
            'id' => $response['response']['id'],
            'name' => $response['response']['title'],
            'description' => $response['response']['description'],
            'categories' => $response['response']['categories'],
            'photos' => $response['response']['photos'],
            'thumbnail' => $response['response']['photos'][array_search('thumbnail', array_column($response['response']['photos'], 'type'))]['url'],
        ];
    }

    public function searchTours($data): Collection
    {
        $path = '/tour-prices?travelDate=' . $data['travel_date'];
        $response = $this->sendRequest($path,'get');
        if ($response['status'] === false) {
            return collect([]);
        }
        return collect($response['response'])->map(function ($tour) {
            return [
                'id' => $tour['tourId'],
                'price' => $tour['price'],
            ];
        });
    }

    public function availableTour($data): bool
    {
        $path = '/tours/' . $data['id'] . '/availability?travelDate=' . $data['travel_date'];
        $response = $this->sendRequest($path,'get');
        if ($response['status'] === false) {
            return collect([]);
        }
        return $response['response']['available'] ?? false;
    }
}
