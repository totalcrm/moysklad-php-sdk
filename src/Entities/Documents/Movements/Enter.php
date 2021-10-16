<?php

namespace TotalCRM\MoySklad\Entities\Documents\Movements;

use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Store;

class Enter extends AbstractMovement
{
    public static $entityName = 'enter';

    public static function getFieldsRequiredForCreation()
    {
        return ['name', Organization::$entityName, Store::$entityName];
    }
}
