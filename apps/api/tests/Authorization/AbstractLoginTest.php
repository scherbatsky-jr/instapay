<?php

namespace Tests\Authorization;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

abstract class AbstractLoginTest extends TestCase
{
    use DatabaseTransactions;

    public const INVALID_ROLE = null;
    public const USER_ROLE = null;

    public function login($username, $password, $withRoles)
    {
        $response = $this->graphql(
            "mutation login {
                login (
                    username: \"$username\"
                    password: \"$password\"
                    withRoles: [\"$withRoles\"]
                ) {
                    auth_tokens {
                        expires_in
                        access_token
                        refresh_token
                    }
                    user {
                        email
                        id
                        username
                    }
                }
            }"
        );

        return $response;
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->login('user@test.com', 'Test4567', static::USER_ROLE);

        $errorMessage = json_decode($response->getContent())->errors[0]->debugMessage;

        $this->assertSame($errorMessage, 'Invalid credentials');
    }

    public function testLoginWithInvalidRole()
    {
        $user = $this->getTestUser(static::USER_ROLE);

        $response = $this->login($user->email, $this->password, static::INVALID_ROLE);

        $errorMessage = json_decode($response->getContent())->errors[0]->debugMessage;

        $this->assertSame($errorMessage, 'Invalid role');
    }

    public function testLoginWithValidCredentials()
    {
        $user = $this->getTestUser(static::USER_ROLE);

        $response = $this->login($user->email, $this->password, static::USER_ROLE);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'data' => [
                'login' => [
                    'auth_tokens' => [
                        'access_token',
                        'expires_in',
                        'refresh_token',
                    ],
                    'user',
                ],
            ],
        ]);

        $responseUser = json_decode($response->getContent())->data->login->user;

        $this->assertNotNull($responseUser);
    }
}
