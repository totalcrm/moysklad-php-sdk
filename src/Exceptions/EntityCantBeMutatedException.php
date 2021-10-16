<?php

namespace TotalCRM\MoySklad\Exceptions;

use \Exception;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;

class EntityCantBeMutatedException extends Exception
{
    public function __construct(DoesNotSupportMutationInterface $entity, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            get_class($entity) . " can't be mutated",
            $code,
            $previous);
    }
}
