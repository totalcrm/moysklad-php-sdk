<?php

namespace TotalCRM\MoySklad\Entities\Misc;

use TotalCRM\MoySklad\Entities\AbstractEntity;

class Publication extends AbstractEntity
{
    public static $entityName = 'operationpublication';

    public static function getFieldsRequiredForCreation()
    {
        return ["template"];
    }
}
