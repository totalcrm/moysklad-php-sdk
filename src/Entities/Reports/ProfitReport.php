<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports\ProfitReportQuerySpecs;
use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\MoySklad;

class ProfitReport extends AbstractReport
{
    public static string $reportName = 'profit';

    public static function byProduct(MoySklad $sklad, ProfitReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'byproduct', $specs);
    }

    public static function byEmployee(MoySklad $sklad, ProfitReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'byemployee', $specs);
    }

}
