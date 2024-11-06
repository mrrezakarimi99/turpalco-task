<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFilterProductRequest;
use App\Http\Resources\ProductCollection;
use App\Repositories\ProductRepository;

class SearchController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository
    )
    {
    }

    /**
     * @param SearchFilterProductRequest $request
     *
     * @return ProductCollection
     */
    public function search(SearchFilterProductRequest $request)
    {
        $products = $this->productRepository->search($request->validated());
        return new ProductCollection($products);
    }
}
