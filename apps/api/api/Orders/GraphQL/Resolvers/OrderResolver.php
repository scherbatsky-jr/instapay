<?php

namespace Api\Orders\GraphQL\Resolvers;

use Api\Orders\Services\OrderService;
use App\GraphQL\AbstractEntitiesResolver;

class OrderResolver extends AbstractEntitiesResolver
{
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }
}
