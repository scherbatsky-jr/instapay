<?php

namespace Api\UserEvents\Models;

class ApiAccessEvent extends UserEvent
{
    protected static $singleTableType = UserEvent::EVENT_TYPE_API_ACCESS;
}
