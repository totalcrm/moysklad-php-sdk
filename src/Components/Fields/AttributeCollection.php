<?php

namespace TotalCRM\MoySklad\Components\Fields;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Misc\Attribute;
use TotalCRM\MoySklad\Registers\EntityRegistry;

/**
 * Class AttributeCollection
 * @package TotalCRM\MoySklad\Components\Fields
 */
class AttributeCollection extends AbstractFieldAccessor
{

    private static $ep;

    public function __construct($fields, AbstractEntity $entity = null)
    {
        if ($fields instanceof static) {
            parent::__construct($fields->getInternal());
        } else {
            parent::__construct(['attrs' => $fields]);
        }
        if (self::$ep === null) {
            self::$ep = EntityRegistry::instance();
        }
    }

    /**
     * Append an attribute
     * @param Attribute $attribute
     */
    public function add(Attribute $attribute): void
    {
        $this->storage->attrs[] = $attribute;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->storage->attrs;
    }

    public function jsonSerialize()
    {
        return $this->storage->attrs;
    }
}
