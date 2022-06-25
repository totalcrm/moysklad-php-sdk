<?php

namespace TotalCRM\MoySklad\Components\Fields;

use TotalCRM\MoySklad\Components\Specs\ConstructionSpecs;
use TotalCRM\MoySklad\Components\Specs\LinkingSpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use RuntimeException;
use Exception;
use stdClass;

/**
 * Class EntityLinker
 * @package TotalCRM\MoySklad\Components\Fields
 */
class EntityLinker extends AbstractFieldAccessor
{

    /**
     * Link an entity to another. Link is a temporary storage before creating or updating entity.
     * Links will be sent along with the entity they're linked to, while relations will not.
     * @param AbstractEntity $entity
     * @param LinkingSpecs|null $specs
     * @throws Exception
     */
    public function link(AbstractEntity $entity, ?LinkingSpecs $specs = null): void
    {
        if (!$specs) {
            /** @var LinkingSpecs $specs */
            $specs = LinkingSpecs::create();
        }
        $name = $specs->name;
        $multiple = $specs->multiple;
        /** @var array $selectedFields */
        $selectedFields = $specs->fields;
        /** @var array $excludedFields */
        $excludedFields = $specs->excludedFields;

        if ($selectedFields && $excludedFields) {
            throw new RuntimeException('Can\'t use "fields" param with "excludedFields"');
        }

        $cls = get_class($entity);
        if ($selectedFields || $excludedFields) {
            $tFields = [];
            foreach ($entity->fields->getInternal() as $k => $v) {
                if ($selectedFields) {
                    if (in_array($k, $selectedFields, true)) {
                        $tFields[$k] = $v;
                    }
                } else if (!in_array($k, $excludedFields, true)) {
                    $tFields[$k] = $v;
                }
            }
            $skladInstance = $entity->getSkladInstance();
            $newEntity = new $cls($skladInstance, $tFields, ConstructionSpecs::create([
                "relations" => false
            ]));
        } else {
            $newEntity = clone $entity;
        }
        if ($name === null) {
            $name = $cls::$entityName;
        }
        if ($multiple) {
            if (empty($this->storage->{$name})) {
                $this->storage->{$name} = [];
            }
            $this->storage->{$name}[] = $newEntity;
        } else {
            $this->storage->{$name} = $newEntity;
        }
    }

    /**
     * @return stdClass
     */
    public function getLinks(): stdClass
    {
        return $this->storage;
    }

    /**
     * @param EntityLinker $otherLinker
     * @return $this
     */
    public function reattachLinks(EntityLinker $otherLinker): self
    {
        $this->storage = $otherLinker->getLinks();
        return $this;
    }
}
