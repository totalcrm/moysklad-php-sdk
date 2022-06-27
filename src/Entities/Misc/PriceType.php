<?php

namespace TotalCRM\MoySklad\Entities\Misc;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

class PriceType extends AbstractEntity implements DoesNotSupportMutationInterface
{
    public static string $entityName = 'pricetype';
    public static ?string $customQueryUrl = 'context/companysettings/pricetype';
}
