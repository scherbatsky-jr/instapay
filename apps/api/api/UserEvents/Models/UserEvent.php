<?php

namespace Api\UserEvents\Models;

use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class UserEvent extends Model
{
    use SingleTableInheritanceTrait;

    public const EVENT_TYPE_API_ACCESS = 3;
    public const EVENT_TYPE_LOGIN = 1;
    public const EVENT_TYPE_LOGOUT = 2;

    public $timestamps = false;

    protected $fillable = [
        'datetime',
        'frequency',
        'type_id',
        'user_id',
    ];

    protected static $singleTableSubclasses = [
        ApiAccessEvent::class,
        LoginEvent::class,
        LogoutEvent::class,
    ];

    protected static $singleTableTypeField = 'type_id';

    protected $table = 'user_events';
}
