<?php

namespace TotalCRM\MoySklad\Entities;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class Country extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'country';
}
