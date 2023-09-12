<?php

namespace App\GraphQL;

use App\AbstractEntityService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class AbstractEntitiesResolver
{
    public const CREATE_FIELD_SELECTION_DEPTH = 0;

    public const FIND_FIELD_SELECTION_DEPTH = 0;

    public const LIST_FIELD_SELECTION_DEPTH = 0;

    public const PAGINATE_FIELD_SELECTION_DEPTH = 0;

    public const UPDATE_FIELD_SELECTION_DEPTH = 0;

    protected $service;

    public function create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->getService()
            ->setUser($context->user())
            ->create(
                $args,
                $resolveInfo->getFieldSelection(static::CREATE_FIELD_SELECTION_DEPTH)
            );
    }

    public function delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->getService()
            ->setUser($context->user())
            ->delete(
                data_get($args, 'id')
            );
    }

    public function find($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->getService()
            ->setUser($context->user())
            ->getById(
                data_get($args, 'id'),
                $resolveInfo->getFieldSelection(static::FIND_FIELD_SELECTION_DEPTH)
            );
    }

    public function list($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->getService()
            ->setUser($context->user())
            ->getAll(
                $resolveInfo->getFieldSelection(static::LIST_FIELD_SELECTION_DEPTH),
                $args
            );
    }

    public function paginate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->getService()
            ->setUser($context->user())
            ->paginate(
                $resolveInfo->getFieldSelection(static::PAGINATE_FIELD_SELECTION_DEPTH),
                $args
            );
    }

    public function update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->getService()
            ->setUser($context->user())
            ->update(
                $args,
                $resolveInfo->getFieldSelection(static::UPDATE_FIELD_SELECTION_DEPTH)
            );
    }

    protected function getService(): AbstractEntityService
    {
        return $this->service;
    }
}
