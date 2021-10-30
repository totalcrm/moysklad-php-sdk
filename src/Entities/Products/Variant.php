<?php

namespace TotalCRM\MoySklad\Entities\Products;

use TotalCRM\MoySklad\Entities\Misc\Characteristics;

class Variant extends AbstractProduct
{
    public static string $entityName = 'variant';

    public static function getFieldsRequiredForCreation()
    {
        return [Product::$entityName, Characteristics::$entityName];
    }
}
