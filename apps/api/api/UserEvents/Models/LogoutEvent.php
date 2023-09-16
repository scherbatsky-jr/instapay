<?php

namespace Api\UserEvents\Models;

class LogoutEvent extends UserEvent
{
    protected static $singleTableType = UserEvent::EVENT_TYPE_LOGOUT;
}
