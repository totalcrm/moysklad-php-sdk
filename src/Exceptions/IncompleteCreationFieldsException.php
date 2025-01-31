<?php

namespace TotalCRM\MoySklad\Exceptions;

use \Exception;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use function json_encode;

/**
 * Not all fields required for creation were passed
 * Class IncompleteCreationFieldsException
 * @package MoySklad\Exceptions
 */
class IncompleteCreationFieldsException extends Exception
{
    public function __construct(AbstractEntity $entity, $code = 0, Exception $previous = null)
    {
        $c = get_class($entity);
        $requiredFields = $c::getFieldsRequiredForCreation();
        $failedFields = [];
        foreach ($requiredFields as $requiredField) {
            if (!isset($entity->links->{$requiredField}) && !isset($entity->{$requiredField})) {
                $failedFields[] = $requiredField;
            }
        }
        parent::__construct(
            "Entity " . $c . " requires these fields to be created: " . \json_encode($requiredFields, JSON_THROW_ON_ERROR) . ", has no these fields at the moment: " . json_encode($failedFields, JSON_THROW_ON_ERROR),
            $code,
            $previous);
    }
}
