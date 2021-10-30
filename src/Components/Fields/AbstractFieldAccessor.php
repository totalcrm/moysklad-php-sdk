<?php

namespace TotalCRM\MoySklad\Components\Fields;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use JsonSerializable;
use stdClass;

/**
 * Class AbstractFieldAccessor
 * @package TotalCRM\MoySklad\Components\Fields
 */
abstract class AbstractFieldAccessor implements JsonSerializable
{
    protected stdClass $storage;
    protected ?AbstractEntity $e;

    public function __construct($fields, AbstractEntity &$entity = null)
    {
        $this->e = $entity;
        $this->storage = new stdClass();
        $this->replace($fields);
    }

    /**
     * Replace fields with new
     * @param $fields
     */
    public function replace($fields): void
    {
        $this->storage = new stdClass();

        if ($fields instanceof static) {
            $fields = $fields->getInternal();
        }
        foreach ($fields as $fieldName => $field) {
            $this->storage->{$fieldName} = $field;
        }
    }

    /**
     * @return stdClass
     */
    public function getInternal(): stdClass
    {
        return $this->storage;
    }

    public function deleteKey($key): void
    {
        unset($this->storage->{$key});
    }

    public function __get($name)
    {
        return $this->storage->{$name};
    }

    public function __set($name, $value)
    {
        $this->storage->{$name} = $value;
    }

    public function __isset($name)
    {
        return isset($this->storage->{$name});
    }

    public function jsonSerialize()
    {
        return $this->getInternal();
    }
}
