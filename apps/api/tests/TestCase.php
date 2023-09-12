<?php

namespace Tests;

use Api\Users\Models\Role;
use Api\Users\Models\User;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\Support\GraphQLErrorExtension;
use Tests\Support\TestClient;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;
    use GraphQLErrorExtension;
    use TestClient;

    public const UNAUTHENTICATED_ERROR_MESSAGE = 'authentication';
    public const UNAUTHENTICATED_GUARD = ['api'];

    public const USER_ROLE = null;

    protected $password = 'Test45678';
    protected $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        if ($user = $this->getTestUser(static::USER_ROLE)) {
            Passport::actingAs($user);
        }
    }

    protected function createUser($role = null)
    {
        if ($role) {
            $faker = $this->getFaker();

            $gender = $faker->boolean ? 1 : 0;
            $given_name = $gender ? $faker->firstNameMale : $faker->firstNameFemale;
            $middle_name = $faker->firstName($gender ? 'male' : 'female');
            $surname = $faker->lastName;
            $username = $faker->userName;

            $user = User::create([
                'email' => $username.'@test.com',
                'username' => $username.'@test.com',
                'password' => Hash::make($this->password),
            ]);

            $user->userRoles()->attach(Role::getRole(static::USER_ROLE)->id);

            return $user;
        }

        return null;
    }

    protected function getFaker()
    {
        return resolve(Faker::class);
    }

    protected function getTestUser($role = null)
    {
        if (!$this->testUser) {
            $this->testUser = $this->createUser($role);
        }

        return $this->testUser;
    }
}
