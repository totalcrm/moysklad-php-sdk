<?php

namespace TotalCRM\MoySklad\Traits;

use TotalCRM\MoySklad\MoySklad;

trait AccessesSkladInstance
{
    protected string $skladHashCode;

    /**
     * Get MoySklad instance used for constructing entity
     * @return MoySklad
     */
    public function getSkladInstance(): MoySklad
    {
        return MoySklad::findInstanceByHash($this->skladHashCode);
    }
}
