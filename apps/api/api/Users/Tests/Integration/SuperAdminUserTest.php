<?php

namespace Api\Users\Tests\Integration;

use Api\Users\Models\Role;

class SuperAdminUserTest extends TestCase
{
    public const USER_ROLE = Role::ROLE_SUPER_ADMIN;

    protected function assertGetAll($response)
    {
        $response->assertJsonStructure([
            'data' => [
                'users' => [
                    '*' => [
                        'id',
                    ],
                ],
            ],
        ]);
    }

    protected function assertMe($response, $data)
    {
        $response->assertExactJson($data);
    }

    protected function assertDailyOperatorStats($response)
    {
        $errorExtension = $this->getErrorExtension();

        $this->assertErrorExtensionEqual($response, $errorExtension);
    }


    protected function assertUpdate($response, $data)
    {
        $response->assertExactJson($data);
    }
}
