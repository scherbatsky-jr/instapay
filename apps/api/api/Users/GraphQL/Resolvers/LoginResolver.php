<?php

namespace Api\Users\GraphQL\Resolvers;

use Api\Users\Services\UserService;
use App\Auth\LoginProxy;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LoginResolver
{
    public function login($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $username = data_get($args, 'username');
        $password = data_get($args, 'password');
        $withRoles = data_get($args, 'withRoles', null);

        return $this->getProxy()
            ->attemptLogin($username, $password, $withRoles);
    }

    public function logout($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->getService()->logout();

        return true;
    }

    public function refresh($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $refresh_token = data_get($args, 'refresh_token', null);

        return $this->getProxy()->attemptRefresh($refresh_token);
    }

    public function signup($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->getService()->signup($args);

        $username = data_get($args, 'username');
        $password = data_get($args, 'password');
        $withRoles = data_get($args, 'withRoles', null);

        return $this->getProxy()
            ->attemptLogin($username, $password, $withRoles);
    }

    protected function getProxy()
    {
        return resolve(LoginProxy::class);
    }

    protected function getService()
    {
        return resolve(UserService::class);
    }
}
