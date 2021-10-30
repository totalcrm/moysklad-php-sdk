<?php

namespace TotalCRM\MoySklad\Lists;

use TotalCRM\MoySklad\Components\Fields\MetaField;
use TotalCRM\MoySklad\Components\Query\RelationQuery;
use TotalCRM\MoySklad\Exceptions\UnknownEntityException;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

/**
 * EntityList with meta. Used for query
 * Class RelationEntityList
 * @package MoySklad\Lists
 */
class RelationEntityList extends EntityList
{
    /**
     * @var null|MetaField
     */
    public $meta = null;

    public function __construct(MoySklad $skladInstance, array $items, MetaField $metaField)
    {
        parent::__construct($skladInstance, $items);
        $this->meta = $metaField;
    }

    public function setMeta(MetaField $metaField)
    {
        $this->meta = $metaField;
    }

    /**
     * Get RelationListQuery object which van be used for getting, filtering and searching lists defined in meta
     * @return RelationQuery
     * @throws UnknownEntityException
     * @see ListQuery
     */
    public function query()
    {
        $relHref = $this->meta->parseRelationHref();
        $sklad = $this->getSkladInstance();
        $res = new RelationQuery($sklad, $this->meta->getClass());
        $res->setCustomQueryUrl(
            ApiUrlRegistry::instance()->getRelationListUrl($relHref['entityClass'], $relHref['entityId'], $relHref['relationClass'])
        );
        return $res;
    }

    /**
     * @param EntityList $list
     * @return static
     * @see EntityList::merge()
     */
    public function merge(EntityList $list)
    {
        return new static($this->getSkladInstance(), array_merge($this->items, $list->toArray()), $this->meta);
    }

    /**
     * @param callable $cb
     * @return static
     * @see EntityList::map()
     */
    public function map(callable $cb)
    {
        return new static($this->getSkladInstance(), array_map($cb, $this->items), $this->meta);
    }


    /**
     * @param callable $cb
     * @return static
     * @see EntityList::filter()
     */
    public function filter(callable $cb)
    {
        return new static($this->getSkladInstance(), array_filter($this->items, $cb), $this->meta);
    }
}
