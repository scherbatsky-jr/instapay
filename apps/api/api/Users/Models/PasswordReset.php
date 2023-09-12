<?php

namespace Api\Users\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public const UPDATED_AT = null;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
    ];

    protected $table = 'password_resets';
}
