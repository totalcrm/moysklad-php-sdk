<?php

namespace TotalCRM\MoySklad\Components\Query;

use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

class EntityQuery extends AbstractQuery
{
    protected static $entityListClass = EntityList::class;

    /**
     * Get entity by id
     * @param $id
     * @param Expand|null $expand Deprecated, use withExpand()
     * @return AbstractEntity
     * @throws \Throwable
     */
    public function byId($id, Expand $expand = null)
    {
        if (!$expand) $expand = $this->expand;
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getByIdUrl($this->entityName, $id),
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
     * @throws \Throwable
     */
    public function bySyncId($id, Expand $expand = null)
    {
        if (!$expand) $expand = $this->expand;
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getBySyncIdUrl($this->entityName, $id),
            ($expand ? ['expand' => $expand->flatten()] : []),
            $this->requestOptions
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }
}
