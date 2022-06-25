<?php

namespace TotalCRM\MoySklad\Components\Query;

use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use Throwable;

class EntityQuery extends AbstractQuery
{
    protected static string $entityListClass = EntityList::class;

    /**
     * Get entity by id
     * @param $id
     * @param Expand|null $expand Deprecated, use withExpand()
     * @return AbstractEntity
     * @throws Throwable
     */
    public function byId($id, Expand $expand = null): AbstractEntity
    {
        if (!$expand) {
            $expand = $this->expand;
        }

        /** @var ApiUrlRegistry $apiUrlRegistry */
        $apiUrlRegistry = ApiUrlRegistry::instance();

        $res = $this->getSkladInstance()->getClient()->get(
            $apiUrlRegistry->getByIdUrl($this->entityName, $id),
            ($expand ? ['expand' => $expand->flatten()] : []),
            $this->requestOptions
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }

    /**
     * Get entity by syncid
     * @param $id
     * @param Expand|null $expand Deprecated, use withExpand()
     * @return AbstractEntity
     * @throws Throwable
     */
    public function bySyncId($id, Expand $expand = null): AbstractEntity
    {
        if (!$expand) {
            $expand = $this->expand;
        }
        /** @var ApiUrlRegistry $apiUrlRegistry */
        $apiUrlRegistry = ApiUrlRegistry::instance();
        $res = $this->getSkladInstance()->getClient()->get(
            $apiUrlRegistry->getBySyncIdUrl($this->entityName, $id),
            ($expand ? ['expand' => $expand->flatten()] : []),
            $this->requestOptions
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }
}
