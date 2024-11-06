<?php

namespace App\Models;

use Illuminate\Http\Request;

abstract class Filter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function handle($builder, \Closure $next);
}
