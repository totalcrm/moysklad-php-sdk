<?php

namespace TotalCRM\MoySklad\Entities\Documents\Returns;

use TotalCRM\MoySklad\Entities\Documents\Movements\Demand;
use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Store;

class SalesReturn extends AbstractReturn
{
    public static $entityName = 'salesreturn';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName, Demand::$entityName, 'agent'];
    }
}
