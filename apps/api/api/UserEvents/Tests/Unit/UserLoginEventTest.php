<?php

namespace Api\UserEvents\Tests\Unit;

use Api\UserEvents\Models\UserEvent;
use Api\Users\Models\Role;
use App\Services\UserEventManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserLoginEventTest extends TestCase
{
    use DatabaseTransactions;

    public const USER_ROLE = Role::ROLE_OPERATOR;

    public function testCreateLoginRecord()
    {
        $user = $this->getTestUser();

        $loginEvent = $this->getUserEventManger()->createLoginRecord($user);

        $this->assertEquals($loginEvent['user_id'], $user->id);
        $this->assertEquals($loginEvent['type_id'], UserEvent::EVENT_TYPE_LOGIN);
        $this->assertNotNull($loginEvent['datetime']);
        $this->assertNull($loginEvent['frequency']);
    }

    protected function getUserEventManger()
    {
        return resolve(UserEventManager::class);
    }
}
