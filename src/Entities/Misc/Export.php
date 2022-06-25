<?php

namespace TotalCRM\MoySklad\Entities\Misc;

use TotalCRM\MoySklad\Entities\AbstractEntity;

class Export extends AbstractEntity
{
    public static string $entityName = 'export';

    public static function getFieldsRequiredForCreation()
    {
        return ["extension"];
    }

    public function getFileLink()
    {
        return $this->fields->file;
    }
}
