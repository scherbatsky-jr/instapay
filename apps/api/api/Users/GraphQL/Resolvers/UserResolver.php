<?php

namespace Api\Users\GraphQL\Resolvers;

use Api\Users\Services\UserService;
use App\Exceptions\Http\WrongPasswordException;
use App\GraphQL\AbstractEntitiesResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserResolver extends AbstractEntitiesResolver
{
    /**
     * UserResolver constructor.
     *
     * @param $userService
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function me($rootValue, array $args, GraphQLContext $context)
    {
        return $context->user();
    }

    public function updatePassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $currentPassword = data_get($args, 'current_password');
        $newPassword = data_get($args, 'new_password');
        $confirmPassword = data_get($args, 'confirm_password');

        if ($newPassword !== $confirmPassword) {
            throw new \Exception('New password and confirm password does not match');
        }

        $data = [
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
        ];

        try {
            $this->getService()->updatePassword($data);
        } catch (\Exception $exception) {
            throw new WrongPasswordException($exception->getMessage());
        }

        $this->getService()->revokeOtherTokens();

        return [
            'success' => true,
            'message' => 'Password updated successfully.',
        ];
    }
}
