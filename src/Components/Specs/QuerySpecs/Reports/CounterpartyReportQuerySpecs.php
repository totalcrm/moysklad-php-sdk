<?php

namespace TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports;


use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;

class CounterpartyReportQuerySpecs extends QuerySpecs
{
    /**
     * @return array
     */
    public function getDefaults(): array
    {
        $res = parent::getDefaults();

        $res['id'] = null;

        return $res;
    }


}
