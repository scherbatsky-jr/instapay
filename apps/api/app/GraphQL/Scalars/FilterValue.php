<?php

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

class FilterValue extends ScalarType
{
    public function parseLiteral($valueNode, array $variables = null)
    {
        if (
            !$valueNode instanceof ListValueNode &&
            !$valueNode instanceof StringValueNode &&
            !$valueNode instanceof IntValueNode &&
            !$valueNode instanceof BooleanValueNode
        ) {
            throw new Error('Query error: Can only parse strings, int and boolean got: '.$valueNode->kind, [$valueNode]);
        }

        if ($valueNode instanceof ListValueNode) {
            $values = [];

            foreach ($valueNode->values as $node) {
                $values[] = $node->value;
            }

            return $values;
        } else {
            return $valueNode->value;
        }
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function serialize($value)
    {
        return $value;
    }
}
