<?php

namespace TotalCRM\MoySklad\Entities\Documents\Movements;


use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Store;

class Loss extends AbstractMovement
{
    public static $entityName = 'loss';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName];
    }
}
