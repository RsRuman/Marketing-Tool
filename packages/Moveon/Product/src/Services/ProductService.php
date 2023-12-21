<?php

namespace Moveon\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Moveon\Product\Repositories\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getProducts($request): LengthAwarePaginator
    {
        return $this->productRepository->all($request);
    }

    /**
     * Get a product
     * @param $id
     * @return Model|null
     */
    public function getProduct($id): ?Model
    {
        return $this->productRepository->find($id);
    }
}
