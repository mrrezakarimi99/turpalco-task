<?php

namespace App\Service\Tours;

use App\Service\Infrastructure\ToursInterface;
use Illuminate\Database\Eloquent\Collection;

class HeavenlyTours extends ToursInterface
{

    public function generateHeaders($data): array
    {
        return [];
    }

    public function getTours(): Collection
    {
        // TODO: Implement getTours() method.
    }

    public function getTour($id): array
    {
        // TODO: Implement getTour() method.
    }

    public function searchTours($data): Collection
    {
        // TODO: Implement searchTours() method.
    }

    public function availableTour($data): Collection
    {
        // TODO: Implement availableTour() method.
    }
}
