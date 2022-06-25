<?php

namespace TotalCRM\MoySklad\Components\Fields;

use TotalCRM\MoySklad\Components\Fields\AbstractFieldAccessor;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\UnknownEntityException;
use TotalCRM\MoySklad\Registers\EntityRegistry;

/**
 * Class MetaField
 * @package TotalCRM\MoySklad\Components\Fields
 */
class MetaField extends AbstractFieldAccessor
{

    private static $ep;

    public function __construct($fields, AbstractEntity &$entity = null)
    {
        if ($fields instanceof static) {
            parent::__construct($fields->getInternal());
        } else {
            parent::__construct($fields);
        }
        if (static::$ep === null) {
            static::$ep = EntityRegistry::instance();
        }
    }

    /**
     * Try to get class from type field
     * @return string|null
     * @throws UnknownEntityException
     */
    public function getClass(): ?string
    {
        if (empty($this->type)) {
            return null;
        }

        if (!isset(static::$ep->entityNames[$this->type])) {
            throw new UnknownEntityException($this->type);
        }

        return static::$ep->entityNames[$this->type];
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * Get relation link in meta
     * @return array
     */
    public function parseRelationHref(): array
    {
        $eHref = explode('/', $this->href);
        $cntHref = count($eHref);
        $entityClass = $eHref[$cntHref - 3];
        $entityId = $eHref[$cntHref - 2];
        $relationClass = $eHref[$cntHref - 1];
        return compact('entityClass', 'entityId', 'relationClass');
    }

    /**
     * Try to get entity id in meta
     * @return null
     */
    public function getId()
    {
        if (!empty($this->href)) {
            $exp = explode("/", $this->href);
            $idExp = explode("?", $exp[count($exp) - 1]);

            return $idExp[0] ?? null;
        }

        return null;
    }

    /**
     * Returns class from stdClass/array meta object
     * @param $metaField
     * @return string|null
     * @throws UnknownEntityException
     */
    public static function getClassFromPlainMeta($metaField): ?string
    {
        return (new static($metaField))->getClass();
    }
}
