<?php

namespace TotalCRM\MoySklad\Components\Specs;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Components\Specs\LinkingSpecs;
use TotalCRM\MoySklad\Components\Specs\CreationSpecs;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;

/**
 * Class AbstractSpecs
 * @package TotalCRM\MoySklad\Components\Specs
 */
abstract class AbstractSpecs
{
    protected static $cachedDefaultSpecs;

    /**
     * AbstractSpecs constructor.
     * @param array $specs
     * @throws UnknownSpecException
     */
    protected function __construct($specs = [])
    {
        $defaults = $this->getDefaults();
        foreach ($defaults as $k => $v) {
            $this->{$k} = $v;
        }
        foreach ($specs as $specName => $spec) {
            if (!array_key_exists($specName, $defaults)) {
                throw new UnknownSpecException($specName);
            }
            $this->{$specName} = $spec;
        }
        if (empty($specs)) {
            static::$cachedDefaultSpecs = $this;
        }
    }

    /**
     * Should be used to construct specs. Returns cached copy if used with empty array
     * @param array|null $specs
     * @return AbstractSpecs|QuerySpecs|LinkingSpecs|CreationSpecs
     * @throws UnknownSpecException
     */
    public static function create(?array $specs = [])
    {
        /** @var self $cl */
        $cl = static::class;
        if (empty($specs) && $cl::$cachedDefaultSpecs !== null) {

            return $cl::$cachedDefaultSpecs;
        }

        return new static($specs);
    }

    /**
     * Create new specs from two existing, does not modify the original ones
     * @param static $otherSpecs
     * @return static
     * @throws UnknownSpecException
     */
    public function mergeWith($otherSpecs)
    {
        $defaults = static::getDefaults();
        $newSpecs = $this->toArray();
        foreach ($otherSpecs as $key => $otherSpec) {
            if ($otherSpec !== $defaults[$key]) {
                $newSpecs[$key] = $otherSpec;
            }
        }
        return static::create($newSpecs);
    }

    /**
     * Converts itself to array
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this;
    }

    /**
     * Specs should be strict, so that's it
     * @param $name
     * @throws UnknownSpecException
     */
    public function __get($name)
    {
        throw new UnknownSpecException($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->$name) ? true : false;
    }

    abstract public function getDefaults();
}
