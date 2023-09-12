<?php

namespace Api\Users\Validators;

use Api\Users\Models\Role;
use App\GraphQL\Validators\AbstractValidator;

class UpdateProfileValidator extends AbstractValidator
{
    public function authorize()
    {
        $user = $this->getUser();

        if ($user && ($user->hasRole(Role::ROLE_ADMIN) ||
            $user->hasRole(Role::ROLE_BROKER_MANAGER) ||
            $user->hasRole(Role::ROLE_BROKER_SALES) ||
            $user->hasRole(Role::ROLE_SUPER_ADMIN) ||
            $user->hasRole(Role::ROLE_OPERATOR)
        )) {
            return true;
        }

        return false;
    }

    public function getRules()
    {
        return [
            'id' => 'required|exists:users,id',
            'profiles.*.locale' => 'required|string',
        ];
    }
}
