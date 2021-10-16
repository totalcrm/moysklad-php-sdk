<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports\CounterpartyReportQuerySpecs;
use TotalCRM\MoySklad\Entities\Counterparty;
use TotalCRM\MoySklad\MoySklad;

class CounterpartyReport extends AbstractReport
{
    public static $reportName = 'counterparty';

    public static function get(MoySklad $sklad, CounterpartyReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, null, $specs);
    }

    public static function byCounterparty(MoySklad $sklad, Counterparty $counterparty)
    {
        return static::queryWithParam($sklad, $counterparty->getMeta()->getId());
    }
}

