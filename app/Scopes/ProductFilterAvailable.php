<?php

namespace App\Scopes;

use App\Models\Filter;

class ProductFilterAvailable extends Filter
{
    public function handle($builder, \Closure $next)
    {
        $builder->whereHas('availabilities', function ($query) {
            $query->where('price', '>', 0);
        });
        $next($builder);
    }
}
