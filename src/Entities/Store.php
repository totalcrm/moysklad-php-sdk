<?php

namespace TotalCRM\MoySklad\Entities;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class Store extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static string $entityName = 'store';
}
