<?php

namespace Api\Users\Services;

use Api\Users\Events\AbstractUserEvent;
use Api\Users\Events\LogoutEvent;
use Api\Users\Events\PasswordChangedEvent;
use Api\Users\Models\User;
use Api\Users\Repositories\UserRepository;
use App\AbstractEntityService;
use App\Auth\LoginProxy;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;

class UserService extends AbstractEntityService
{
    protected $dispatcher;

    public function __construct(
        Dispatcher $dispatcher,
        UserRepository $repository
    ) {
        $this->dispatcher = $dispatcher;
        $this->repository = $repository;
    }

    public function dailyOperatorStats($id)
    {
        $user = $this->getById($id);

        return $this->getRepository()->dailyOperatorStats($user);
    }

    public function getOperatorStats($startDate, $endDate)
    {
        return $this->getRepository()->getOperatorStats($startDate, $endDate);
    }

    public function getUserByEmail($email)
    {
        return $this->getRepository()->getUserByEmail($email);
    }

    public function logout()
    {
        $user = Auth::user();

        $this->getProxy()->logout();

        $this->getDispatcher()->dispatch(new LogoutEvent($user));
    }

    public function resetPassword(User $user, $newPassword): ?User
    {
        $user = $this->getRepository()->resetPassword($user, $newPassword);

        if (null === $user) {
            return null;
        }

        $this->fireEvent(new PasswordChangedEvent($user));

        return $user;
    }

    public function revokeOtherTokens()
    {
        $this->getProxy()->revokeOtherTokens();
    }

    public function updatePassword($data)
    {
        $user = Auth::user();

        return $this->getRepository()->updatePassword($user, $data);
    }

    protected function beforeEntityUpdate($user, $data = [])
    {
        unset($data['profiles']);

        return $data;
    }

    protected function fireEvent(AbstractUserEvent $event)
    {
        $this->getDispatcher()->dispatch($event);
    }

    protected function getDispatcher()
    {
        return $this->dispatcher;
    }

    protected function getProxy()
    {
        return resolve(LoginProxy::class);
    }

    protected function onEntityUpdate($user, array $fields = [], $data = [])
    {
        $user->profiles()->delete();
        $newProfiles = data_get($data, 'profiles', []);
        $user->profiles()->createMany($newProfiles);
    }
}
