<?php

namespace TotalCRM\MoySklad\Exceptions;

use \Exception;
use TotalCRM\MoySklad\Entities\AbstractEntity;

/**
 * Entity has no "id" field
 * Class EntityHasNoIdException
 * @package MoySklad\Exceptions
 */
class EntityHasNoIdException extends Exception
{
    /**
     * EntityHasNoIdException constructor.
     * @param AbstractEntity $entity
     * @param int|null $code
     * @param Exception|null $previous
     */
    public function __construct(AbstractEntity $entity, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Entity " . get_class($entity) . " has no id",
            $code,
            $previous);
    }
}
