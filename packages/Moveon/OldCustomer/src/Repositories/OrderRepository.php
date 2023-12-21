<?php

namespace Moveon\Customer\Repositories;

use Moveon\Customer\Models\Order;

class OrderRepository
{
    public function create($data) {
        return Order::create($data);
    }
}
