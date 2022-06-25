<?php

namespace TotalCRM\MoySklad\Entities\Documents\Returns;

use TotalCRM\MoySklad\Entities\Documents\Movements\Supply;
use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Store;

class PurchaseReturn extends AbstractReturn
{
    public static string $entityName = 'purchasereturn';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName, Supply::$entityName, 'agent'];
    }
}
