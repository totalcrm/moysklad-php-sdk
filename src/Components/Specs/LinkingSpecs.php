<?php

namespace TotalCRM\MoySklad\Components\Specs;

use TotalCRM\MoySklad\Components\Specs\AbstractSpecs;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;

/**
 * Class LinkingSpecs
 * @package TotalCRM\MoySklad\Components\Specs
 */
class LinkingSpecs extends AbstractSpecs
{
    protected static $cachedDefaultSpecs;

    /**
     * Get possible variables for spec
     *  name: what name to use when linking
     *  fields: what fields will be used when linking, others will be discarded
     *  excludeFields: what fields will be discarded when linking, can't be used with "fields" param
     *  multiple: flags if same named links should be put into array
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'name' => null,
            'fields' => null,
            'excludedFields' => null,
            'multiple' => false
        ];
    }

    /**
     * Should be used to construct specs. Returns cached copy if used with empty array
     * @param array|null $specs
     * @return LinkingSpecs
     * @throws UnknownSpecException
     */
    public static function create(?array $specs = []): LinkingSpecs
    {
        return parent::create($specs);
    }
}
