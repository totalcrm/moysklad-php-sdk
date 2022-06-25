<?php

namespace TotalCRM\MoySklad\Components;

use Exception;
use RuntimeException;

/**
 * Class Expand
 * @package TotalCRM\MoySklad\Components
 */
class Expand
{
    private ?array $params;

    private function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Create an instance of expand
     * @param $params
     * @return static
     * @throws Exception
     */
    public static function create($params)
    {
        if (!is_array($params)) {
            throw new RuntimeException('Expand params must be an array');
        }
        return new static($params);
    }

    /**
     * Convert itself to string
     * @return string
     */
    public function flatten(): string
    {
        return implode(',', $this->params);
    }
}
