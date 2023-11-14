<?php

namespace Api\Products\GraphQL\Resolvers;

use Api\Products\Services\ImageService;
use App\GraphQL\AbstractEntitiesResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ImageResolver extends AbstractEntitiesResolver
{
    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    public function deleteImages($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this
            ->getService()
            ->setUser($context->user())
            ->deleteImages(data_get($args, 'ids'));
    }

    public function updateImages($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $files = [];
        $updateFiles = data_get($args, 'files');

        foreach ($updateFiles as $data) {
            $file = $this->getService()
                ->setUser($context->user())
                ->update($data);

            $files[] = $file;
        }

        return $files;
    }

    public function uploadFiles($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this
            ->getService()
            ->setUser($context->user())
            ->uploadImages(data_get($args, 'images'));
    }
}
