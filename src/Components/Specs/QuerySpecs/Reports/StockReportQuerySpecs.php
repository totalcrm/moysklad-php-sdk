<?php

namespace TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;

/**
 * Class StockReportQuerySpecs
 * @package TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports
 */
class StockReportQuerySpecs extends QuerySpecs
{
    protected static $cachedDefaultSpecs;

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        $res = parent::getDefaults();

        $res['store.id'] = null;
        $res['product.id'] = null;
        $res['consignment.id'] = null;
        $res['variant.id'] = null;
        $res['productFolder.id'] = null;
        $res['search'] = null;
        $res['stockMode'] = null;
        $res['groupBy'] = null;
        $res['moment'] = null;
        $res['characteristics'] = null;
        $res['includeRelated'] = null;
        $res['operation.id'] = null;

        return $res;
    }
}
