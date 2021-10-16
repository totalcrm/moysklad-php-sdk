<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\MoySklad;

class DashboardReport extends AbstractReport
{
    public static $reportName = 'dashboard';

    public static function day(MoySklad $sklad)
    {
        return static::queryWithParam($sklad, 'day');
    }

    public static function week(MoySklad $sklad)
    {
        return static::queryWithParam($sklad, 'week');
    }

    public static function month(MoySklad $sklad)
    {
        return static::queryWithParam($sklad, 'month');
    }
}
