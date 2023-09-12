<?php

namespace App\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\DirectiveException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;

class ValidateDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return '';
    }

    public function handleField(FieldValue $value, \Closure $next): FieldValue
    {
        $resolver = $value->getResolver();

        return $next(
            $value->setResolver(
                function ($root, $args, $context, ResolveInfo $resolveInfo) use ($resolver) {
                    $validatorName = $this->directiveArgValue('validator');
                    $fieldName = $resolveInfo->fieldName;

                    if (!$validatorName) {
                        throw new DirectiveException("A `validator` argument must be supplied on the @validate directive on field {$fieldName}");
                    }

                    $validator = app(
                        $validatorName,
                        [
                            'data' => $args,
                            'user' => $context->user,
                        ]
                    );

                    $validator->validate();

                    return call_user_func_array($resolver, func_get_args());
                }
            )
        );
    }

    public function name(): string
    {
        return 'validate';
    }
}
