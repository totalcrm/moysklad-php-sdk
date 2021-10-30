<?php

namespace TotalCRM\MoySklad\Entities\Misc;

use TotalCRM\MoySklad\Entities\AbstractEntity;

class Publication extends AbstractEntity
{
    public static string $entityName = 'operationpublication';

    public static function getFieldsRequiredForCreation()
    {
        return ["template"];
    }
}
