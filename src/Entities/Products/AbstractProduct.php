<?php

namespace TotalCRM\MoySklad\Entities\Products;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use stdClass;

class AbstractProduct extends AbstractEntity
{
    public static string $entityName = '_a_product';
    private ?array $assortment;

    /**
     * @param mixed|null $name
     * @return mixed|stdClass|null
     */
    public function getSalePrice($name = '')
    {
        if (empty($this->salePrices)) {
            return null;
        }

        if (!$name) {
            return $this->salePrices;
        }
        foreach ($this->salePrices as $sp) {
            if ($sp->priceType === $name) {
                return $sp;
            }
        }

        return null;
    }
}
