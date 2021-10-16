<?php

namespace TotalCRM\MoySklad\Traits;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\ApiResponseException;
use TotalCRM\MoySklad\Exceptions\EntityHasNoIdException;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

trait Deletes
{

    /**
     * Delete entity, throws exception if not found
     * @param bool $getIdFromMeta
     * @return bool
     * @throws EntityHasNoIdException
     */
    public function delete($getIdFromMeta = false)
    {
        /**
         * @var AbstractEntity $this
         */
        if (empty($this->fields->id)) {
            if (!$getIdFromMeta || !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        $this->getSkladInstance()->getClient()->delete(
            ApiUrlRegistry::instance()->getDeleteUrl(static::$entityName, $id)
        );
        return true;
    }
}
