<?php

namespace Api\Users\Repositories;

use Api\Users\Models\PasswordReset;
use App\AbstractEntityRepository;

class PasswordResetRepository extends AbstractEntityRepository
{
    public function deleteByToken($token)
    {
        return $this->getModelClass()::query()
            ->where('token', $token)
            ->delete();
    }

    public function getByEmail($email)
    {
        return $this->getModelClass()::query()
            ->where('email', $email)
            ->first();
    }

    public function getByToken($token)
    {
        return $this->getModelClass()::query()
            ->where('token', $token)
            ->first();
    }

    public function getModelClass(): string
    {
        return PasswordReset::class;
    }
}
