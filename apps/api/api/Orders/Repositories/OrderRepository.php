<?php

namespace Api\Orders\Repositories;

use Api\Orders\Models\Order;
use App\AbstractEntityRepository;

class OrderRepository extends AbstractEntityRepository
{
    public function getModelClass(): string
    {
        return Order::class;
    }
}
