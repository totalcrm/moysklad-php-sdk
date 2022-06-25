<?php

namespace TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;

/**
 * Class SalesReportQuerySpecs
 * @package TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports
 */
class SalesReportQuerySpecs extends QuerySpecs
{
    protected static $cachedDefaultSpecs;

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        $res = parent::getDefaults();
        $res['product.id'] = null;
        $res['counterparty.id'] = null;
        $res['organization.id'] = null;
        $res['store.id'] = null;
        $res['project.id'] = null;
        $res['retailStore.id'] = null;
        return $res;
    }
}
