<?php

namespace TotalCRM\MoySklad\Traits;

use TotalCRM\MoySklad\Components\Fields\MetaField;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\ApiResponseException;
use TotalCRM\MoySklad\Exceptions\EntityHasNoIdException;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use Throwable;

trait Deletes
{

    /**
     * Delete entity, throws exception if not found
     * @param bool $getIdFromMeta
     * @return bool
     * @throws EntityHasNoIdException
     * @throws Throwable
     */
    public function delete($getIdFromMeta = false): bool
    {
        /**  @var AbstractEntity $entity */
        $entity = $this;
        if (empty($entity->fields->id ?? null)) {
            /** @var MetaField $meta */
            $meta = $entity->getMeta();
            if (!$getIdFromMeta || !$id = $meta->getId()) {
                throw new EntityHasNoIdException($entity);
            }
        } else {
            $id = $entity->id ?? null;
        }
        /** @var ApiUrlRegistry $apiUrlRegistry */
        $apiUrlRegistry = ApiUrlRegistry::instance();
        /** @var MoySklad $skladInstance */
        $skladInstance = $entity->getSkladInstance();
        $skladInstance->getClient()->delete(
            $apiUrlRegistry->getDeleteUrl(static::$entityName, $id)
        );

        return true;
    }
}
