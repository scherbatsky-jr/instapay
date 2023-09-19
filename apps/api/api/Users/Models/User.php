<?php

namespace Api\Users\Models;

use Api\Users\Models\Profile as UserProfile;
use App\Exceptions\Http\InvalidCredentialsException;
use App\Exceptions\UserDisabledException;
use App\Exceptions\UserNotFoundException;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;
    use MustVerifyEmail;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disabled',
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'confirmation_token',
        'password',
        'remember_token',
    ];

    protected $table = 'users';

    public function activeRoles()
    {
        return $this->userRoles->filter(function ($user_role) {
            $start = $user_role->pivot->start_date;
            $end = $user_role->pivot->end_date;
            $now = Carbon::now();

            return (!$start || $now->gte($start)) && (!$end || $now->lte($end));
        });
    }

    public static function findForAuth($username, $withRoles): User
    {
        if ($withRoles && count($withRoles)) {
            $roleIds = Role::query()
                ->whereIn('name', $withRoles)
                ->pluck('id');

            $user = static::query()
                ->where('username', $username)
                ->orWhere('email', $username)
                ->first();

            if (!$user) {
                throw new InvalidCredentialsException('Invalid credentials');
            }

            $user = static::query()
                ->where(function ($query) use ($username) {
                    $query->where('username', $username)
                        ->orWhere('email', $username);
                })
                ->leftJoin('user_roles', 'users.id', 'user_roles.user_id')
                ->whereIn('user_roles.role_id', $roleIds)
                ->first();

            if (!$user) {
                throw new \Exception('Invalid role');
            }

            if ($user->isDisabled()) {
                throw new UserDisabledException($user);
            }

            return static::query()
                ->find($user->user_id);
        }

        return static::findForPassport($username);
    }

    public static function findForPassport($username): User
    {
        $user = static::query()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->isDisabled()) {
            throw new UserDisabledException($user);
        }

        return $user;
    }

    public function hasRole($roles)
    {
        $result = false;

        $userRoles = $this->activeRoles()->pluck('name')->toArray();

        if (!is_array($userRoles)) {
            return $result;
        }

        $userRoles = array_map('strtoupper', $userRoles);

        if (!count($userRoles)) {
            $result = false;
        } elseif (is_array($roles) && count(array_intersect(array_map('strtoupper', $roles), $userRoles)) === count($roles)) {
            $result = true;
        } elseif (is_string($roles) && in_array(strtoupper($roles), $userRoles)) {
            $result = true;
        }

        return $result;
    }

    public function isAdmin()
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }

    public function isAdminUser()
    {
        return $this->hasRole(Role::ROLE_ADMIN) ||
            $this->hasRole(Role::ROLE_SUPER_ADMIN);
    }

    public function isBrokerManager()
    {
        return $this->hasRole(Role::ROLE_BROKER_MANAGER);
    }

    public function isBrokerSalesperson()
    {
        return $this->hasRole(Role::ROLE_BROKER_SALES);
    }

    public function isBrokerUser()
    {
        return $this->hasRole(Role::ROLE_BROKER_MANAGER) ||
            $this->hasRole(Role::ROLE_BROKER_SALES);
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function isOperator()
    {
        return $this->hasRole(Role::ROLE_OPERATOR);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole(Role::ROLE_SUPER_ADMIN);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    public function scopeRole($query, $role)
    {
        $roles = is_array($role) ? $role : [$role];

        $now = Carbon::now()->format('Y-m-d');

        $query->whereHas('userRoles', function ($query) use ($roles, $now) {
            $query->whereIn('name', $roles)
                ->where(function ($q) use ($now) {
                    $q->where('end_date', '>', $now)
                        ->orWhereNull('end_date');
                })
                ->where(function ($q) use ($now) {
                    $q->where('start_date', '<=', $now)
                        ->orWhereNull('start_date');
                });
        });
    }

    public function userRoles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->using(UserRole::class)
            ->withPivot(['start_date', 'end_date', 'last_allocated_at']);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(
            function ($user) {
                $user->profile()->create([]);
            }
        );
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
