<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports\CounterpartyReportQuerySpecs;
use TotalCRM\MoySklad\Entities\Counterparty;
use stdClass;
use Throwable;

class CounterpartyReport extends AbstractReport
{
    public static string $reportName = 'counterparty';

    /**
     * @param MoySklad $sklad
     * @param CounterpartyReportQuerySpecs|null $specs
     * @return stdClass|string
     * @throws Throwable
     */
    public static function get(MoySklad $sklad, CounterpartyReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, null, $specs);
    }

    /**
     * @param MoySklad $sklad
     * @param Counterparty $counterparty
     * @return stdClass|string
     * @throws Throwable
     */
    public static function byCounterparty(MoySklad $sklad, Counterparty $counterparty)
    {
        return static::queryWithParam($sklad, $counterparty->getMeta()->getId());
    }
}

