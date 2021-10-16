<?php

namespace TotalCRM\MoySklad\Entities\Products;

use TotalCRM\MoySklad\Entities\Assortment;

class Consignment extends AbstractProduct
{
    public static
        $entityName = 'consignment';

    public static function getFieldsRequiredForCreation()
    {
        return ["label", Assortment::class];
    }
}
