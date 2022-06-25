<?php

namespace TotalCRM\MoySklad\Components\Specs;

use TotalCRM\MoySklad\Components\Specs\AbstractSpecs;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;

/**
 * Class CreationSpecs
 * @package TotalCRM\MoySklad\Components\Specs
 */
class CreationSpecs extends AbstractSpecs
{
    /**
     * Get possible variables for spec
     * @return array
     */
    public function getDefaults(): array
    {
        return [];
    }

    /**
     * Should be used to construct specs. Returns cached copy if used with empty array
     * @param array|null $specs
     * @return CreationSpecs
     * @throws UnknownSpecException
     */
    public static function create(?array $specs = []): CreationSpecs
    {
        return parent::create($specs);
    }
}
