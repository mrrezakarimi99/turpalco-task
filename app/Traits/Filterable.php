<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter($builder)
    {
        return app(Pipeline::class)
            ->send($builder)
            ->through($this->getFilters())
            ->thenReturn();
    }
}
