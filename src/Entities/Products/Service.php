<?php

namespace TotalCRM\MoySklad\Entities\Products;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class Service extends AbstractProduct
{
    use RequiresOnlyNameForCreation;
    public static string $entityName = 'service';
}
