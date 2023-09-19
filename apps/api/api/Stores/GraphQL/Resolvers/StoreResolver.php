<?php

namespace Api\Stores\GraphQL\Resolvers;

use Api\Stores\Services\StoreService;
use App\GraphQL\AbstractEntitiesResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class StoreResolver extends AbstractEntitiesResolver
{
    public function __construct(StoreService $service)
    {
        $this->service = $service;
    }
}