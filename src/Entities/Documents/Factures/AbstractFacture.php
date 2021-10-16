<?php

namespace TotalCRM\MoySklad\Entities\Documents\Factures;

use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;

class AbstractFacture extends AbstractDocument implements DoesNotSupportMutationInterface
{
    public static $entityName = 'a_facture';
}
