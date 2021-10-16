<?php

namespace TotalCRM\MoySklad\Entities;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class Uom extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'uom';
}
