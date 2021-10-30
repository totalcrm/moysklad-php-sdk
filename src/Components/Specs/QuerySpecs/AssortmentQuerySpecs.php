<?php

namespace TotalCRM\MoySklad\Components\Specs\QuerySpecs;

class AssortmentQuerySpecs extends QuerySpecs
{
    protected static $cachedDefaultSpecs;

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        $res = parent::getDefaults();

        $res['stockstore'] = null;
        $res['stockmoment'] = null;
        $res['scope'] = null;
        $res['stockmode'] = null;

        return $res;
    }
}
