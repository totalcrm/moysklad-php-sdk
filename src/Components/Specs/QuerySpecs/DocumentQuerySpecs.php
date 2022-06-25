<?php

namespace TotalCRM\MoySklad\Components\Specs\QuerySpecs;


class DocumentQuerySpecs extends QuerySpecs
{
    /**
     * @return array
     */
    public function getDefaults(): array
    {
        $res = parent::getDefaults();

        $res['state.name'] = null;
        $res['state.id'] = null;
        $res['organization.id'] = null;
        $res['isDeleted'] = null;

        return $res;
    }
}
