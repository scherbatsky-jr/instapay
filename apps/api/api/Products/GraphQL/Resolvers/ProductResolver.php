<?php

namespace Api\Products\GraphQL\Resolvers;

use Api\Products\Services\ProductService;
use App\GraphQL\AbstractEntitiesResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductResolver extends AbstractEntitiesResolver
{
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function addImage($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->getService()
            ->setUser($context->user())
            ->addImage(
                $args,
                $resolveInfo->getFieldSelection(static::CREATE_FIELD_SELECTION_DEPTH)
            );
    }
}
