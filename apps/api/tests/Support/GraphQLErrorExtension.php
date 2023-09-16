<?php

namespace Tests\Support;

use GraphQL\Error\Error;
use Illuminate\Support\Arr;
use Illuminate\Testing\Assert as PHPUnit;

trait GraphQLErrorExtension
{
    protected function assertErrorExtensionEqual($response, $errorExtension)
    {
        PHPUnit::assertEquals(
            json_encode(Arr::sortRecursive($errorExtension)),
            json_encode(Arr::sortRecursive($response->baseResponse->original['errors'][0]['extensions']))
        );
    }

    protected function getErrorExtension($category = Error::CATEGORY_INTERNAL, $guards = null)
    {
        return $guards ? [
            'category' => $category,
            'guards' => $guards,
        ] :
        [
            'category' => $category,
        ];
    }
}
