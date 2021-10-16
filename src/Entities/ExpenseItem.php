<?php

namespace TotalCRM\MoySklad\Entities;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class ExpenseItem extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'expenseitem';
}
