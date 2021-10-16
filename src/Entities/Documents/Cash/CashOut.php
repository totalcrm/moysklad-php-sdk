<?php

namespace TotalCRM\MoySklad\Entities\Documents\Cash;

use TotalCRM\MoySklad\Entities\Organization;

class CashOut extends AbstractCash
{
    public static $entityName = 'cashout';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'agent', 'expenseItem'];
    }
}
