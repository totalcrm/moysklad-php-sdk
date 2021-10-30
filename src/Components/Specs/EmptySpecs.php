<?php

namespace TotalCRM\MoySklad\Components\Specs;

use TotalCRM\MoySklad\Components\Specs\AbstractSpecs;

class EmptySpecs extends AbstractSpecs
{
    /**
     * Get possible variables for spec
     * @return array
     */
    public function getDefaults(): array
    {
        return [];
    }
}
