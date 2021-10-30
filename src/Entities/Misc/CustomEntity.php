<?php

namespace TotalCRM\MoySklad\Entities\Misc;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class CustomEntity extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static string $entityName = 'customentity';
}
