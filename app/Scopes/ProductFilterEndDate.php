<?php

namespace App\Scopes;

use App\Models\Filter;

class ProductFilterEndDate extends Filter
{
    public function handle($builder, \Closure $next)
    {
        $builder->where('end_date', '<=', $this->request->get('end_date', now()->addWeeks(2)));
        $next($builder);
    }
}
