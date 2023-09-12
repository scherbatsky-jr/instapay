<?php

namespace Api\Users\Tests\Integration;

use Api\Users\Models\User;
use Api\Users\Models\Role;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function testGetAll()
    {
        User::factory()->count(5)->create();

        $response = $this->graphql(
            '{
                users {
                    email,
                    gender,
                    id,
                    profiles {
                        given_name,
                        locale
                    },
                    username
                }
            }'
        );

        $response->assertSuccessful();

        $this->assertGetAll($response);
    }

    public function testMe()
    {
        $response = $this->graphql(
            '{
                me {
                    email,
                    gender,
                    id,
                    profiles {
                        given_name,
                        locale
                    },
                    username
                }
            }'
        );

        $response->assertSuccessful();

        $this->assertMe(
            $response,
            [
                'data' => [
                    'me' => [
                        'email'=> $this->testUser?->email,
                        'gender' => $this->testUser?->gender,
                        'id' => $this->testUser?->id,
                        'profiles' => [
                            [
                                'given_name' => $this->testUser?->profiles[0]?->given_name,
                                'locale' => $this->testUser?->profiles[0]?->locale
                            ],
                            [
                                'given_name' => $this->testUser?->profiles[1]?->given_name,
                                'locale' => $this->testUser?->profiles[1]?->locale
                            ]
                        ],
                        'username' => $this->testUser?->username
                    ],
                ],
            ]
        );
    }

    public function testDailyOperatorStats()
    {
        $user = User::factory()->create();

        $user->userRoles()->attach(Role::getRole(static::USER_ROLE)?->id);

        $response = $this->graphql(
            "{
                dailyOperatorStats(
                    user_id: $user->id
                ) {
                    call_failed
                    call_later
                    leads_processed
                    pre_qualified
                    rejected
                    qualified
                }
            }"
        );

        $response->assertSuccessful();

        $this->assertDailyOperatorStats($response);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();

        $response = $this->graphql(
            "mutation updateProfile {
                updateProfile (
                    id: $user->id,
                    gender: 1,
                    profiles: [
                        {
                            given_name: \"TestUser_en\",
                            locale: \"en\"
                        },
                        {
                            given_name: \"TestUser_th\",
                            locale: \"th\"
                        }
                    ]
                ) {
                    email
                    gender
                    id
                    profiles {
                        given_name,
                        locale
                    }
                    username
                }
            }"
        );

        $response->assertSuccessful();

        $this->assertUpdate(
            $response,
            [
                'data' => [
                    'updateProfile' => [
                        'email'=> $user->email,
                        'gender' => 1,
                        'id' => $user->id,
                        'profiles' => [
                            [
                                'given_name' => 'TestUser_en',
                                'locale' => 'en'
                            ],
                            [
                                'given_name' => 'TestUser_th',
                                'locale' => 'th'
                            ]
                        ],
                        'username' => $user->username
                    ],
                ],
            ]
        );
    }

    abstract protected function assertGetAll($response);

    abstract protected function assertMe($response, $data);

    abstract protected function assertDailyOperatorStats($response);

    abstract protected function assertUpdate($response, $data);
}
