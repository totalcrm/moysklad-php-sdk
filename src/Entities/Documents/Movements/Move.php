<?php

namespace TotalCRM\MoySklad\Entities\Documents\Movements;

use TotalCRM\MoySklad\Entities\Organization;

class Move extends AbstractMovement
{
    public static string $entityName = 'move';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'targetStore', 'sourceStore'];
    }
}
