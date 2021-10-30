<?php

namespace TotalCRM\MoySklad\Components\Specs;

use TotalCRM\MoySklad\Components\Specs\AbstractSpecs;

class ConstructionSpecs extends AbstractSpecs
{
    protected static $cachedDefaultSpecs;

    /**
     * Get possible variables for spec
     *  relations: entity may have relations
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            "relations" => true
        ];
    }
}
