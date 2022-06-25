<?php

namespace TotalCRM\MoySklad\Entities;

class Currency extends AbstractEntity
{
    public static string $entityName = 'currency';

    public static function getFieldsRequiredForCreation()
    {
        return ["name", "code", "isoCode"];
    }
}
