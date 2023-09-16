<?php

namespace Api\UserEvents\Tests\Unit;

use Api\UserEvents\Models\UserEvent;
use Api\Users\Models\Role;
use App\Services\UserEventManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserLogoutEventTest extends TestCase
{
    use DatabaseTransactions;

    public const USER_ROLE = Role::ROLE_OPERATOR;

    public function testCreateLogoutRecord()
    {
        $user = $this->getTestUser();

        $logoutEvent = $this->getUserEventManger()->createLogoutRecord($user);

        $this->assertEquals($logoutEvent['user_id'], $user->id);
        $this->assertEquals($logoutEvent['type_id'], UserEvent::EVENT_TYPE_LOGOUT);
        $this->assertNotNull($logoutEvent['datetime']);
        $this->assertNull($logoutEvent['frequency']);
    }

    protected function getUserEventManger()
    {
        return resolve(UserEventManager::class);
    }
}
