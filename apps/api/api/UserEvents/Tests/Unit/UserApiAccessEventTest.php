<?php

namespace Api\UserEvents\Tests\Unit;

use Api\UserEvents\Models\UserEvent;
use Api\Users\Models\Role;
use App\Services\UserEventManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserApiAccessEventTest extends TestCase
{
    use DatabaseTransactions;

    public const USER_ROLE = Role::ROLE_OPERATOR;

    public function testUserApiAccessEventRecord()
    {
        $user = $this->getTestUser();

        $accessEvent = $this->getUserEventManger()->logUserAccess($user);

        $this->assertEquals($accessEvent['user_id'], $user->id);
        $this->assertEquals($accessEvent['type_id'], UserEvent::EVENT_TYPE_API_ACCESS);
        $this->assertNotNull($accessEvent['datetime']);
        $this->assertEquals($accessEvent['frequency'], 1);

        $accessEvent = $this->getUserEventManger()->logUserAccess($user);

        $this->assertEquals($accessEvent['frequency'], 2);
    }

    protected function getUserEventManger()
    {
        return resolve(UserEventManager::class);
    }
}
