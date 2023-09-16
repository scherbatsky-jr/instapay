<?php

namespace Api\UserEvents\Models;

class LoginEvent extends UserEvent
{
    protected static $singleTableType = UserEvent::EVENT_TYPE_LOGIN;
}
