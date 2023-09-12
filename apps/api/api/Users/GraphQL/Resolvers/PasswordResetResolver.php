<?php

namespace Api\Users\GraphQL\Resolvers;

use Api\Users\Services\PasswordResetService;
use Api\Users\Services\UserService;
use App\Exceptions\Http\TokenException;
use App\Exceptions\Http\UserNotFoundException;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PasswordResetResolver
{
    protected $service;
    protected $userService;

    /**
     * PasswordResetResolver constructor.
     */
    public function __construct(PasswordResetService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    public function getService()
    {
        return $this->service;
    }

    public function requestPasswordReset($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $email = data_get($args, 'email');

        $url = data_get($args, 'url');

        return $this->getService()->sendPasswordResetMail($email, $url);
    }

    public function resetPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $newPassword = data_get($args, 'password');
        $token = data_get($args, 'token');

        $passwordReset = $this->getService()->getByToken($token);

        if (is_null($passwordReset)) {
            throw new TokenException('Invalid reset token');
        }

        $user = $this->getService()->getUserByEmail($passwordReset->email);

        if (!$user) {
            throw new UserNotFoundException('Password reset user not found');
        }

        $result = $this->getUserService()->resetPassword($user, $newPassword);

        if ($result) {
            $this->getService()->deleteByToken($token);

            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
        ];
    }

    protected function getUserService(): UserService
    {
        return $this->userService;
    }
}
