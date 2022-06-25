<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports\SalesReportQuerySpecs;
use TotalCRM\MoySklad\MoySklad;

class SalesReport extends AbstractReport
{
    public static string $reportName = 'sales';

    public static function byProduct(MoySklad $sklad, SalesReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'byproduct', $specs);
    }

    public static function byVariant(MoySklad $sklad, SalesReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'byvariant', $specs);
    }

    public static function byEmployee(MoySklad $sklad, SalesReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'byemployee', $specs);
    }

    public static function byCounterparty(MoySklad $sklad, SalesReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'bycounterparty', $specs);
    }
}
