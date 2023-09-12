<?php

namespace Api\Users\Tests\Integration;

use Api\Users\Models\Role;

class OperatorUserTest extends TestCase
{
    public const USER_ROLE = Role::ROLE_OPERATOR;

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
        $response->assertJsonStructure([
            'data' => [
                'dailyOperatorStats' => [
                    'leads_processed',
                ],
            ],
        ]);
    }

    protected function assertUpdate($response, $data)
    {
        $response->assertExactJson($data);
    }
}
