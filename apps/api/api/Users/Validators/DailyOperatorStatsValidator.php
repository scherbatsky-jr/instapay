<?php

namespace Api\Users\Validators;

use Api\Users\Models\Role;
use Api\Users\Models\User;
use App\GraphQL\Validators\AbstractValidator;

class DailyOperatorStatsValidator extends AbstractValidator
{
    // TODO [US: 2022-07-19] Need to provide authorization for admin/super admin, if this endpoint is to accessed by the users of respective roles.
    public function authorize()
    {
        $user = $this->getUser();

        if ($user && $user->hasRole(Role::ROLE_OPERATOR)) {
            return true;
        }

        return false;
    }

    public function getRules()
    {
        $data = $this->getData();

        return [
            'user_id' => [
                'required',
                function ($attribute, $value, $fail) use ($data) {
                    $userId = data_get($data, 'user_id');
                    $user = User::find($userId);

                    if (!$user) {
                        return $fail('user_does_not_exists');
                    }

                    if (!$user->hasRole(Role::ROLE_OPERATOR)) {
                        return $fail('user_has_no_operator_role');
                    }
                },
            ],
        ];
    }
}
