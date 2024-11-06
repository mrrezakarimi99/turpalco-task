<?php

namespace App\Scopes;

use App\Models\Filter;

class ProductFilterStartDate extends Filter
{
    public function handle($builder, \Closure $next)
    {
        $builder->where('start_date', '>=', $this->request->get('start_date', now()));
        $next($builder);
    }
}
