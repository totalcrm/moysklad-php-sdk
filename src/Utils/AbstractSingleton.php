<?php

namespace TotalCRM\MoySklad\Utils;

abstract class AbstractSingleton
{
    protected static $instance = null;

    protected function __construct()
    {
    }

    /**
     * @return static|null
     */
    final public static function instance(): ?AbstractSingleton
    {
        if (is_null(static::$instance)) {
            $class = static::class;
            static::$instance = new $class();
        }

        return static::$instance;
    }
}
