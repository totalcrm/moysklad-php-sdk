<?php

namespace TotalCRM\MoySklad\Entities\Products;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class Bundle extends AbstractProduct
{
    use RequiresOnlyNameForCreation;
    public static string $entityName = 'bundle';
}
