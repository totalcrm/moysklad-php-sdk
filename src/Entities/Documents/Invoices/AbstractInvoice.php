<?php

namespace TotalCRM\MoySklad\Entities\Documents\Invoices;

use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Organization;

class AbstractInvoice extends AbstractDocument
{
    public static string $entityName = 'a_invoice';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'agent'];
    }
}
