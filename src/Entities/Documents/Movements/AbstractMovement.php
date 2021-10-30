<?php

namespace TotalCRM\MoySklad\Entities\Documents\Movements;

use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Store;

class AbstractMovement extends AbstractDocument
{
    public static string $entityName = "a_movement";

    public static function getFieldsRequiredForCreation()
    {
        return ['name', 'agent', Organization::$entityName, Store::$entityName];
    }
}
