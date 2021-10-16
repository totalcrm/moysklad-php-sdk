<?php

namespace TotalCRM\MoySklad\Entities\Documents\Orders;

use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Organization;

class AbstractOrder extends AbstractDocument
{
    public static $entityName = '_a_order';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'agent'];
    }
}
