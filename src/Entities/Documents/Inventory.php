<?php

namespace TotalCRM\MoySklad\Entities\Documents;

use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Store;

class Inventory extends AbstractDocument
{
    public static string $entityName = 'inventory';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName];
    }
}
