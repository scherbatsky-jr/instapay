<?php

namespace App\Utils;

use Carbon\Carbon;

class Calendar
{
    public function getNextWorkingDay()
    {
        $next_date = Carbon::now()->addDay();

        while ($next_date->isSaturday() || $next_date->isSunday()) {
            $next_date->addDay();
        }

        return $next_date->setTimeFromTimeString($this->getWorkDayStart());
    }

    public function getWorkDayEnd()
    {
        return config('app.workday.end');
    }

    public function getWorkDayStart()
    {
        return config('app.workday.start');
    }
}
