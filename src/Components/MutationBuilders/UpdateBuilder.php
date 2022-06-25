<?php

namespace TotalCRM\MoySklad\Components\MutationBuilders;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\EntityHasNoIdException;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use Throwable;

/**
 * Class UpdateBuilder
 * @package TotalCRM\MoySklad\Components\MutationBuilders
 */
class UpdateBuilder extends AbstractMutationBuilder
{
    /**
     * Update entity with current fields
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     * @throws Throwable
     */
    public function execute(): AbstractEntity
    {
        $entity = &$this->e;
        $entityClass = get_class($entity);
        $id = $entity->findEntityId();
        /** @var ApiUrlRegistry $apiUrlRegistry */
        $apiUrlRegistry = ApiUrlRegistry::instance();
        $res = $entity->getSkladInstance()->getClient()->put(
            $apiUrlRegistry->getUpdateUrl($entityClass::$entityName, $id),
            $entity->mergeFieldsWithLinks()
        );

        return new $entityClass($entity->getSkladInstance(), $res);
    }
}
