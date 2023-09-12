<?php

namespace Api\Users\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    public $timestamps = false;

    protected $dates = [
        'end_date',
        'start_date',
    ];

    protected $fillable = [
        'end_date',
        'role_id',
        'start_date',
        'user_id',
    ];

    protected $table = 'user_roles';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
