<?php

namespace TotalCRM\MoySklad\Entities\Misc;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\EntityCantBeMutatedException;
use TotalCRM\MoySklad\Exceptions\EntityHasNoIdException;
use Throwable;

class Webhook extends AbstractEntity
{

    public const
        ACTION_CREATE = 'CREATE',
        ACTION_UPDATE = 'UPDATE',
        ACTION_DELETE = 'DELETE';

    public static string $entityName = 'webhook';

    /**
     * @return array
     */
    public static function getFieldsRequiredForCreation()
    {
        return ['url', 'action', 'entityType'];
    }

    /**
     * @return AbstractEntity
     * @throws Throwable
     * @throws EntityCantBeMutatedException
     * @throws EntityHasNoIdException
     */
    public function disable(): AbstractEntity
    {
        $this->fields->enabled = false;
        return $this->update();
    }

    /**
     * @return AbstractEntity
     * @throws Throwable
     * @throws EntityCantBeMutatedException
     * @throws EntityHasNoIdException
     */
    public function enable(): AbstractEntity
    {
        $this->fields->enabled = true;
        return $this->update();
    }
}
