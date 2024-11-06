<?php

namespace App\Scopes;

use App\Models\Filter;

class ProductFilterStartTime extends Filter
{
    public function handle($builder, \Closure $next)
    {
        $builder->whereHas('availabilities', function ($query) {
            $query->whereDate('start_time', '>=', $this->request->get('start_date', now()));
        });
        $next($builder);
    }
}
