<?php

namespace TotalCRM\MoySklad\Components\MutationBuilders;

use TotalCRM\MoySklad\Components\MassRequest;
use TotalCRM\MoySklad\Components\Specs\CreationSpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\IncompleteCreationFieldsException;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;
use Throwable;

/**
 * Class CreationBuilder
 * @package TotalCRM\MoySklad\Components\MutationBuilders
 */
class CreationBuilder extends AbstractMutationBuilder
{
    protected CreationSpecs $specs;

    /**
     * CreationBuilder constructor.
     * @param AbstractEntity $entity
     * @param CreationSpecs|null $specs
     * @throws UnknownSpecException
     */
    public function __construct(AbstractEntity $entity, CreationSpecs $specs = null)
    {
        parent::__construct($entity);
        if (!$specs) {
            /** @var CreationSpecs $specs */
            $specs = CreationSpecs::create();
        }
        $this->specs = $specs;
    }

    /**
     * @return AbstractEntity
     * @throws IncompleteCreationFieldsException
     * @throws Throwable
     */
    public function execute(): AbstractEntity
    {
        $this->e->validateFieldsRequiredForCreation();
        $mr = new MassRequest($this->e->getSkladInstance(), $this->e);

        return $mr->create()->get(0);
    }
}
