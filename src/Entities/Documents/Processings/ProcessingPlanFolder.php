<?php

namespace TotalCRM\MoySklad\Entities\Documents\Processings;


class ProcessingPlanFolder extends AbstractProcessing
{
    public static string $entityName = 'processingplanfolder';

    public static function getFieldsRequiredForCreation()
    {
        return ['name', 'materials', 'products'];
    }
}
