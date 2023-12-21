<?php

namespace Moveon\Customer\Repositories;

use Moveon\Customer\Models\Product;

class ProductRepository
{
    public function create($data) {
        return Product::create($data);
    }
}
