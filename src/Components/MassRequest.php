<?php

namespace TotalCRM\MoySklad\Components;

use Exception;
use RuntimeException;
use Throwable;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use TotalCRM\MoySklad\Traits\AccessesSkladInstance;

/**
 * Class MassRequest
 * @package TotalCRM\MoySklad\Components
 */
class MassRequest
{
    use AccessesSkladInstance;

    /**
     * @var AbstractEntity[] $stack
     */
    private array $stack = [];

    public function __construct(MoySklad $sklad, $stack = [])
    {
        $this->skladHashCode = $sklad->hashCode();
        if (!is_array($stack)) {
            $stack = [$stack];
        }
        foreach ($stack as $row) {
            $this->stack[] = $row;
        }
    }

    /**
     * Add entity to internal array
     * @param AbstractEntity $entity
     * @throws Exception
     */
    public function push(AbstractEntity $entity): void
    {
        if (!empty($this->stack) && get_class($this->stack[0]) !== get_class($entity)) {
            throw new RuntimeException("Mass request can only hold entities of same type");
        }
        $this->stack[] = $entity;
    }

    /**
     * Run creation for stored entities
     * @return EntityList
     * @throws Throwable
     */
    public function create(): EntityList
    {
        $className = get_class($this->stack[0]);
        /** @var ApiUrlRegistry $apiUrlRegistry */
        $apiUrlRegistry = ApiUrlRegistry::instance();
        $url = $apiUrlRegistry->getCreateUrl($className::$entityName);
        $res = $this->getSkladInstance()->getClient()->post(
            $url,
            array_map(static function (AbstractEntity $e) {
                return $e->mergeFieldsWithLinks();
            }, $this->stack)
        );
        return $this->recreateEntityList($className, $res);
    }

    /**
     * Returns new EntityList after performing API operation
     * @param $className
     * @param $reqResult
     * @return EntityList
     */
    private function recreateEntityList($className, $reqResult): EntityList
    {
        $res = [];
        if (is_array($reqResult) === false) {
            $reqResult = [$reqResult];
        }
        foreach ($reqResult as $i => $item) {
            /**
             * @var AbstractEntity $newEntity
             */
            $newEntity = new $className($this->getSkladInstance(), $item);
            $newEntity->links->reattachLinks($this->stack[$i]->links);
            $newEntity->fields->replace($this->stack[$i]->fields);
            $res[] = $newEntity;
        }
        return new EntityList($this->getSkladInstance(), $res);
    }
}
