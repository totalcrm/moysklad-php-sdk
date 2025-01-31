<?php

namespace TotalCRM\MoySklad\Entities;

use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Components\Fields\AttributeCollection;
use TotalCRM\MoySklad\Components\Fields\EntityFields;
use TotalCRM\MoySklad\Components\Fields\EntityLinker;
use TotalCRM\MoySklad\Components\Fields\EntityRelation;
use TotalCRM\MoySklad\Components\Fields\MetaField;
use TotalCRM\MoySklad\Components\MutationBuilders\CreationBuilder;
use TotalCRM\MoySklad\Components\MutationBuilders\UpdateBuilder;
use TotalCRM\MoySklad\Components\Query\EntityQuery;
use TotalCRM\MoySklad\Components\Query\RelationQuery;
use TotalCRM\MoySklad\Components\Specs\ConstructionSpecs;
use TotalCRM\MoySklad\Components\Specs\CreationSpecs;
use TotalCRM\MoySklad\Components\Specs\LinkingSpecs;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\Audit\AbstractAudit;
use TotalCRM\MoySklad\Entities\Audit\Audit;
use TotalCRM\MoySklad\Entities\Audit\AuditEvent;
use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Misc\Attribute;
use TotalCRM\MoySklad\Entities\Misc\State;
use TotalCRM\MoySklad\Exceptions\EntityCantBeMutatedException;
use TotalCRM\MoySklad\Exceptions\EntityHasNoIdException;
use TotalCRM\MoySklad\Exceptions\EntityHasNoMetaException;
use TotalCRM\MoySklad\Exceptions\IncompleteCreationFieldsException;
use TotalCRM\MoySklad\Exceptions\Relations\RelationDoesNotExistException;
use TotalCRM\MoySklad\Exceptions\Relations\RelationIsList;
use TotalCRM\MoySklad\Exceptions\Relations\RelationIsSingle;
use TotalCRM\MoySklad\Exceptions\UnknownEntityException;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use TotalCRM\MoySklad\Traits\AccessesSkladInstance;
use TotalCRM\MoySklad\Traits\Deletes;
use Throwable;
use stdClass;

/**
 * Class AbstractEntity
 * @package TotalCRM\MoySklad\Entities
 */
abstract class AbstractEntity implements \JsonSerializable
{
    use AccessesSkladInstance, Deletes;

    public static string $entityName = '_a_entity';
    protected static ?string $customQueryUrl = null;
    public EntityFields $fields;
    public EntityLinker $links;
    public ?EntityRelation $relations = null;
    public MetaField $meta;
    public AttributeCollection $attributes;

    /**
     * AbstractEntity constructor.
     * @param MoySklad $skladInstance
     * @param array $fields
     * @param ConstructionSpecs|null $specs
     * @throws UnknownSpecException
     */
    public function __construct(MoySklad $skladInstance, $fields = [], ConstructionSpecs $specs = null)
    {
        if (!$specs) {
            $specs = ConstructionSpecs::create();
        }
        if (is_array($fields) === false && is_object($fields) === false) {
            $fields = [$fields];
        }
        $this->fields = new EntityFields($fields, $this);
        $this->links = new EntityLinker([], $this);
        $this->relations = new EntityRelation([], static::class, $this);
        $this->skladHashCode = $skladInstance->hashCode();
        $this->processConstructionSpecs($specs);
    }

    /**
     * Returns new AbstractEntity inheritor with chosen class
     * @param $targetClass
     * @return mixed| AbstractEntity
     */
    public function transformToClass($targetClass)
    {
        return new $targetClass($this->getSkladInstance(), $this->fields->getInternal());
    }

    /**
     * Returns new AbstractEntity inheritor with class taken from meta
     * @return $this
     * @throws EntityHasNoMetaException
     */
    public function transformToMetaClass(): self
    {
        $eMeta = $this->getMeta();
        if ($eMeta) {
            return $this->transformToClass(
                $eMeta->getClass()
            );
        }

        throw new EntityHasNoMetaException($this);
    }

    /**
     * Returns meta object
     * @return MetaField|null
     */
    public function getMeta()
    {
        return $this->fields->getMeta();
    }

    /**
     * @return string
     * @throws EntityHasNoIdException
     */
    public function findEntityId(): string
    {
        $id = null;
        if (empty($this->fields->id)) {
            if (!$id = $this->getMeta()->getId()) {
                throw new EntityHasNoIdException($this);
            }
        } else {
            $id = $this->fields->id;
        }
        return $id;
    }

    /**
     * Replaces current fields with response entity fields, expand may be used to load relations
     * @param Expand|null $expand
     * @return $this
     * @throws EntityHasNoIdException
     * @throws Throwable
     */
    public function fresh(Expand $expand = null): self
    {
        $id = $this->findEntityId();
        $sklad = $this->getSkladInstance();
        $queriedEntity = static::query($sklad)->byId($id, $expand);
        $this->replaceFields($queriedEntity);

        return $this;
    }

    /**
     * Copy fields and relations from other entity
     * @param AbstractEntity $entity
     * @return $this
     */
    public function replaceFields(AbstractEntity $entity): self
    {
        $this->fields = new EntityFields($entity->fields, $this);
        $this->relations = new EntityRelation($entity->relations, get_class($this), $this);

        return $this;
    }

    /**
     * Get EntityQuery object which van be used for getting, filtering and searching lists
     * @param MoySklad $skladInstance
     * @param QuerySpecs|null $querySpecs
     * @return EntityQuery
     * @throws \Exception
     */
    public static function query(MoySklad &$skladInstance, QuerySpecs $querySpecs = null)
    {
        $static = static::class;
        $eq = new EntityQuery($skladInstance, static::class, $querySpecs);
        $eq->setResponseAttributesMapper($static, "listQueryResponseAttributeMapper");
        if (!is_null(static::$customQueryUrl)) {
            $eq->setCustomQueryUrl(static::$customQueryUrl);
        }

        return $eq;
    }

    /**
     * Get a CreationBuilder
     * @param CreationSpecs|null $specs
     * @return CreationBuilder
     * @throws EntityCantBeMutatedException
     */
    public function buildCreation(CreationSpecs $specs = null)
    {
        $this->checkMutationPossibility();

        return new CreationBuilder($this, $specs);
    }

    /**
     * Get an UpdateBuilder
     * @return UpdateBuilder
     * @throws EntityCantBeMutatedException
     */
    public function buildUpdate(): UpdateBuilder
    {
        $this->checkMutationPossibility();

        return new UpdateBuilder($this);
    }

    /**
     * Create with existing fields
     * @param CreationSpecs|null $specs
     * @return AbstractEntity|AbstractDocument
     * @throws EntityCantBeMutatedException
     * @throws IncompleteCreationFieldsException
     * @throws Throwable
     */
    public function create(CreationSpecs $specs = null)
    {
        $this->checkMutationPossibility();

        return $this->buildCreation($specs)->execute();
    }

    /**
     * Update with existing fields
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     * @throws EntityCantBeMutatedException
     * @throws Throwable
     */
    public function update(): AbstractEntity
    {
        $this->checkMutationPossibility();

        return $this->buildUpdate()->execute();
    }

    /**
     * Puts links to fields before creation
     * @return array
     * @internal
     */
    public function mergeFieldsWithLinks(): array
    {
        $res = [];
        $links = $this->links->getLinks();
        foreach ($this->fields->getInternal() as $k => $v) {
            $res[$k] = $v;
        }
        foreach ($links as $k => $v) {
            $res[$k] = $v;
        }
        return $res;
    }

    /**
     * Puts relations to links
     * @return $this
     * @throws \Exception
     * @internal
     */
    public function copyRelationsToLinks(): self
    {
        foreach ($this->relations->getInternal() as $k => $v) {
            $this->links->link($v, LinkingSpecs::create([
                "name" => $k
            ]));
        }

        return $this;
    }

    /**
     * Tries to load single relation defined on entity
     * @param $relationName
     * @param null $expand
     * @return AbstractEntity
     * @throws RelationIsList
     * @throws RelationDoesNotExistException
     */
    public function loadRelation($relationName, $expand = null): AbstractEntity
    {
        return $this->relations->fresh($relationName, $expand);
    }

    /**
     * Get RelationListQuery object which van be used for getting, filtering and searching lists of relations
     * @param $relationName
     * @return RelationQuery|TotalCRM\MoySklad\Components\Query\RelationQuery
     * @throws RelationDoesNotExistException
     * @throws RelationIsSingle
     * @throws UnknownEntityException
     */
    public function relationListQuery($relationName)
    {
        $static = static::class;
        $rq = $this->relations->listQuery($relationName);
        $rq->setResponseAttributesMapper($static, 'listQueryResponseAttributeMapper');

        return $rq;
    }

    /**
     * @return EntityList
     * @throws \Exception
     */
    public function getAuditEvents(): EntityList
    {
        $eq = new EntityQuery($this->getSkladInstance(), AuditEvent::class);
        $eq->setResponseAttributesMapper(AbstractAudit::class, "listQueryResponseAttributeMapper");
        if (static::class === Audit::class) {
            $eq->setCustomQueryUrl(ApiUrlRegistry::instance()->getAuditEventsUrl($this->fields->id));
        } else {
            $eq->setCustomQueryUrl(ApiUrlRegistry::instance()->getAuditEventsEntityUrl(
                static::$entityName, $this->fields->id
            ));
        }

        return $eq->getList();
    }

    /**
     * Get entity metadata information
     * @param MoySklad $sklad
     * @return stdClass|string
     * @throws Throwable
     */
    public static function getMetaData(MoySklad $sklad)
    {
        $res = $sklad->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataUrl(static::$entityName)
        );

        $attributes = ($res->attributes ?? []);

        $attributes = new EntityList($sklad, $attributes);
        $res->attributes = $attributes->map(function ($e) use ($sklad) {
            return new Attribute($sklad, $e);
        });

        $states = new EntityList($sklad, $res->states ?? []);
        $res->states = $states->map(function ($e) use ($sklad) {
            return new State($sklad, $e);
        });

        return $res;
    }

    /**
     * @return array
     */
    public static function getFieldsRequiredForCreation()
    {
        return [];
    }

    /**
     * @throws IncompleteCreationFieldsException
     */
    public function validateFieldsRequiredForCreation(): void
    {
        $requiredFields = static::getFieldsRequiredForCreation();
        foreach ($requiredFields as $requiredField) {
            if (
                !isset($this->links->{$requiredField}) && !isset($this->{$requiredField})
            ) {
                throw new IncompleteCreationFieldsException($this);
            }
        }
    }

    public static function boot(): void
    {
    }

    /**
     * @param ConstructionSpecs $specs
     */
    protected function processConstructionSpecs(ConstructionSpecs $specs): void
    {
        if ($specs->relations) {
            $this->relations = EntityRelation::createRelations($this->getSkladInstance(), $this);
            foreach ($this->relations->getInternal() as $k => $v) {
                $this->fields->deleteKey($k);
            }
        }
    }

    /**
     * @throws EntityCantBeMutatedException
     */
    protected function checkMutationPossibility(): void
    {
        if ($this instanceof DoesNotSupportMutationInterface) {
            throw new EntityCantBeMutatedException($this);
        }
    }

    /**
     * @param stdClass $attributes
     * @param $skladInstance
     * @return stdClass
     */
    public static function listQueryResponseAttributeMapper($attributes, $skladInstance)
    {
        return $attributes;
    }

    /**
     * @return mixed|stdClass
     */
    public function jsonSerialize()
    {
        $res = $this->fields->getInternal();
        $res->relations = $this->relations;

        return $res;
    }

    public function __get($name)
    {
        return $this->fields->{$name};
    }

    public function __set($name, $value)
    {
        $this->fields->{$name} = $value;
    }

    public function __isset($name)
    {
        return isset($this->fields->{$name});
    }
}
