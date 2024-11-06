<?php

namespace App\Scopes;

use App\Models\Filter;

class ProductFilterEndTime extends Filter
{
    public function handle($builder, \Closure $next)
    {
        $builder->whereHas('availabilities', function ($query) {
            $query->whereDate('end_time', '<=', $this->request->get('end_date', now()->addWeeks(2)));
        });
        $next($builder);
    }
}
