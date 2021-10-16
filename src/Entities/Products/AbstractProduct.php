<?php

namespace TotalCRM\MoySklad\Entities\Products;

use TotalCRM\MoySklad\Entities\AbstractEntity;

class AbstractProduct extends AbstractEntity
{
    public static $entityName = '_a_product';

    /**
     * @param $name
     * @return null|\stdClass
     */
    public function getSalePrice($name)
    {
        if (empty($this->salePrices)) return null;
        foreach ($this->salePrices as $sp) {
            if ($sp->priceType == $name) {
                return $sp;
            }
        }
        return null;
    }
}
