<?php

namespace TotalCRM\MoySklad\Traits;

trait RequiresOnlyNameForCreation
{
    public static function getFieldsRequiredForCreation()
    {
        return ["name"];
    }
}
