<?php

namespace TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports;


use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;

class CounterpartyReportQuerySpecs extends QuerySpecs
{
    protected static $cachedDefaultSpecs = null;

    public function getDefaults()
    {
        $res = parent::getDefaults();
        $res['id'] = null;
        return $res;
    }


}
