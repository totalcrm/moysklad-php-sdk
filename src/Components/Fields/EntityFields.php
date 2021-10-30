<?php

namespace TotalCRM\MoySklad\Components\Fields;

use TotalCRM\MoySklad\Components\Fields\MetaField;
use TotalCRM\MoySklad\Components\Fields\AttributeCollection;
use TotalCRM\MoySklad\Components\Fields\ImageField;

/**
 * Class EntityFields
 * @package TotalCRM\MoySklad\Components\Fields
 */
class EntityFields extends AbstractFieldAccessor
{

    /**
     * Replace fields. Creates MetaField and AttributeCollection within itself
     * @param $fields
     */
    public function replace($fields): void
    {
        if ($fields instanceof self) {
            $fields = $fields->getInternal();
        }

        foreach ($fields as $fieldName => $field) {
            switch ($fieldName) {
                case "meta":
                    $this->storage->meta = new MetaField($field, $this->e);
                    break;
                case "attributes":
                    $this->storage->attributes = new AttributeCollection($field, $this->e);
                    break;
                case "image":
                    $this->storage->image = new ImageField($field, $this->e);
                    break;
                default:
                    $this->storage->{$fieldName} = $field;
                    break;
            }
        }
    }

    /**
     * @return MetaField|null
     */
    public function getMeta()
    {
        return $this->storage->meta ?: null;
    }
}
