<?php

namespace TotalCRM\MoySklad\Entities\Documents\Processings;

use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Organization;

class ProcessingOrder extends AbstractDocument
{
    public static string $entityName = 'processingorder';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'processingPlan', 'positions'];
    }
}
