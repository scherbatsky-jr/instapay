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

    public function stats($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        return $this->getService()
            ->setUser($context->user())
            ->stats(data_get($args, 'id'));
    }
}
