<?php

namespace TotalCRM\MoySklad\Components\Fields;

use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Components\Query\RelationQuery;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\Relations\RelationDoesNotExistException;
use TotalCRM\MoySklad\Exceptions\Relations\RelationIsList;
use TotalCRM\MoySklad\Exceptions\Relations\RelationIsSingle;
use TotalCRM\MoySklad\Exceptions\UnknownEntityException;
use TotalCRM\MoySklad\Lists\RelationEntityList;
use TotalCRM\MoySklad\MoySklad;

/**
 * Class EntityRelation
 * @package TotalCRM\MoySklad\Components\Fields
 */
class EntityRelation extends AbstractFieldAccessor
{
    private $relatedByClass;

    /**
     * EntityRelation constructor.
     * @param $fields
     * @param $relatedByClass
     * @param AbstractEntity|null $entity
     */
    public function __construct($fields, $relatedByClass, AbstractEntity &$entity = null)
    {
        parent::__construct($fields, $entity);
        $this->relatedByClass = $relatedByClass;
    }

    /**
     * @param MoySklad $sklad
     * @param AbstractEntity $entity
     * @return static
     */
    public static function createRelations(MoySklad $sklad, AbstractEntity $entity)
    {
        $internalFields = $entity->fields->getInternal();
        $foundRelations = [];
        foreach ($internalFields as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $ar = $v;
                array_walk($ar, static function ($e, $i) use ($k, $ar, &$foundRelations, $sklad) {
                    if ($i === 'meta') {
                        /** @var MetaField $mf */
                        $mf = new MetaField($e);
                        if (isset($mf->size)) {
                            $foundRelations[$k] = new RelationEntityList($sklad, [], $mf);
                        } else {
                            $class = $mf->getClass();
                            if ($class) {
                                $foundRelations[$k] = new $class($sklad, $ar);
                            }
                        }
                    }
                });
            }
        }
        return new static($foundRelations, get_class($entity));
    }


    /**
     * @param $relationName
     * @param Expand|null $expand
     * @return AbstractEntity
     * @throws RelationDoesNotExistException
     * @throws RelationIsList
     */
    public function fresh($relationName, Expand $expand = null): AbstractEntity
    {
        $this->checkRelationExists($relationName);
        /**
         * @var AbstractEntity $rel
         */
        $rel = $this->storage->{$relationName};
        if ($rel instanceof RelationEntityList) {
            throw new RelationIsList($relationName, $this->relatedByClass);
        }
        $c = get_class($rel);
        $queriedEntity = $c::query($rel->getSkladInstance())->byId($rel->fields->meta->getId(), $expand);
        return $rel->replaceFields($queriedEntity);
    }

    /**
     * @param $relationName
     * @return RelationQuery
     * @throws RelationDoesNotExistException
     * @throws RelationIsSingle
     * @throws UnknownEntityException
     */
    public function listQuery($relationName): RelationQuery
    {
        $this->checkRelationExists($relationName);
        /**
         * @var RelationEntityList $rel
         */
        $rel = $this->storage->{$relationName};
        if ($rel instanceof AbstractEntity) {
            throw new RelationIsSingle($relationName, $this->relatedByClass);
        }
        return $rel->query();
    }

    /**
     * @param $entityClass
     * @return static|null
     */
    public function find($entityClass): ?EntityRelation
    {
        foreach ($this->storage as $key => $value) {
            if (get_class($value) === $entityClass) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return array_keys((array)$this->storage);
    }

    /**
     * @param $relationName
     * @throws RelationDoesNotExistException
     */
    private function checkRelationExists($relationName): void
    {
        if (empty($this->storage->{$relationName})) {
            throw new RelationDoesNotExistException($relationName, $this->relatedByClass);
        }
    }
}
