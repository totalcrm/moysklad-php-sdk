<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Components\Specs\EmptySpecs;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

abstract class AbstractReport extends AbstractEntity implements DoesNotSupportMutationInterface
{
    public static $entityName = 'report';
    public static $reportName = 'a_report';

    /**
     * @param MoySklad $sklad
     * @param null $param
     * @param QuerySpecs|null $specs
     * @return \stdClass
     */
    protected static function queryWithParam(MoySklad $sklad, $param = null, QuerySpecs $specs = null)
    {
        if (!$specs) $specs = EmptySpecs::create();
        if ($param === null) {
            $url = ApiUrlRegistry::instance()->getReportUrl(static::$reportName);
        } else {
            $url = ApiUrlRegistry::instance()->getReportWithParamUrl(static::$reportName, $param);
        }
        return $sklad->getClient()->get($url, $specs->toArray());
    }
}
