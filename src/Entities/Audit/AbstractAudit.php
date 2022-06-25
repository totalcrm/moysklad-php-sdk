<?php

namespace TotalCRM\MoySklad\Entities\Audit;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Employee;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use stdClass;

/**
 * Class AbstractAudit
 * @package TotalCRM\MoySklad\Entities\Audit
 */
class AbstractAudit extends AbstractEntity
{
    public static string $entityName = "a_audit";

    /**
     * @param stdClass $attributes
     * @param $skladInstance
     * @return stdClass
     * @throws UnknownSpecException
     */
    public static function listQueryResponseAttributeMapper($attributes, $skladInstance)
    {
        if (isset($attributes->context->employee)) {
            $attributes->context->employee = new Employee($skladInstance, $attributes->context->employee);
        }
        return $attributes;
    }
}
