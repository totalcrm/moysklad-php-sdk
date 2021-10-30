<?php

namespace TotalCRM\MoySklad\Entities;

use TotalCRM\MoySklad\Traits\RequiresOnlyNameForCreation;

class Project extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static string $entityName = 'project';
}
