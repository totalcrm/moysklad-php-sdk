<?php

namespace TotalCRM\MoySklad\Entities\Documents\PriceLists;

use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Organization;

class PriceList extends AbstractDocument
{
    public static string $entityName = 'pricelist';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'columns'];
    }
}
