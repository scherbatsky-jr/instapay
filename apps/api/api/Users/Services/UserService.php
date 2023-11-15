<?php

namespace Api\Users\Services;

use Api\Users\Events\AbstractUserEvent;
use Api\Users\Events\LogoutEvent;
use Api\Users\Events\PasswordChangedEvent;
use Api\Users\Models\Role;
use Api\Users\Models\User;
use Api\Users\Models\UserRole;
use Api\Users\Repositories\UserRepository;
use App\AbstractEntityService;
use App\Auth\LoginProxy;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function signup($data)
    {
        $password = data_get($data, 'password');

        if ($password) {
            $password = Hash::make($password);

            $data['email'] = $data['username'];
            $data['password'] = $password;

            $profile = data_get($data, 'profile');
            $roles = data_get($data, 'withRoles');

            unset($data['profile']);
            unset($data['withRoles']);

            $user = $this->getRepository()->create($data);

            if ($user) {
                foreach($roles as $role) {
                    $roleModel = Role::where('name', $role)->first();

                    if ($roleModel) {
                        UserRole::create([
                            'user_id' => $user->id,
                            'role_id' => $roleModel->id
                        ]);
                    }
                }

                $user->profile->update($profile);

                $user->save();

                return $user;
            }
        }

        return false;
    }

    public function updatePassword($data)
    {
        $user = Auth::user();

        return $this->getRepository()->updatePassword($user, $data);
    }

    protected function beforeEntityUpdate($user, $data = [])
    {
        unset($data['profile']);

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
        $user->profile()->delete();
        $newProfiles = data_get($data, 'profile', []);
        $user->profile()->create($newProfiles);
    }
}
