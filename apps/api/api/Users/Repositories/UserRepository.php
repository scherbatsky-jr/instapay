<?php

namespace Api\Users\Repositories;

use Api\Users\Models\Role;
use Api\Users\Models\User;
use App\AbstractEntityRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends AbstractEntityRepository
{
    public function filterRole($query, $method, $clause_operator, $value)
    {
        $role = Role::getRole($value);

        $query
            ->{$method}('user_roles.role_id', $clause_operator, $role->id);
    }

    public function getModelClass(): string
    {
        return User::class;
    }

    public function getUserByEmail($email)
    {
        return $this->getModelClass()::query()
            ->where('email', $email)
            ->first();
    }

    public function joinRole($query)
    {
        if (!$this->checkQueryHasJoinedTable($query, 'user_roles')) {
            $query->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id');
        }
    }

    public function resetPassword(User $user, $newPassword)
    {
        $user->password = Hash::make($newPassword);

        $user->save();

        return $user;
    }

    public function updatePassword(User $user, array $data)
    {
        $doesPasswordMatch = Hash::check($data['current_password'], $user->password);

        if ($doesPasswordMatch) {
            $user->password = Hash::make($data['new_password']);

            $user->save();
        } else {
            throw new \Exception('wrong current password');
        }

        return $user;
    }
}
