<?php

namespace Api\Products\GraphQL\Resolvers;

use Api\Products\Services\ProductService;
use App\GraphQL\AbstractEntitiesResolver;

class ProductResolver extends AbstractEntitiesResolver
{
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }
}
