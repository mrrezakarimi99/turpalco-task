<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function __construct(
        private Product $product
    )
    {
    }

    public function search(array $filters): Collection
    {
        return $this->product
            ->with('availabilities')
            ->filter()
            ->get();
    }
}
