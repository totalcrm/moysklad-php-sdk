<?php

namespace TotalCRM\MoySklad\Entities\Documents\CommissionReports;

use TotalCRM\MoySklad\Entities\Contract;
use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Organization;

class AbstractCommissionReport extends AbstractDocument
{
    public static $entityName = 'a_commissionreport';

    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'agent', Contract::$entityName, 'commissionPeriodStart', 'commissionPeriodEnd'];
    }
}
