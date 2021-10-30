<?php

namespace TotalCRM\MoySklad\Entities\Folders;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class AbstractFolder extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static string $entityName = '_a_folder';
}
