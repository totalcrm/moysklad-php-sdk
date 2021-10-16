<?php

namespace TotalCRM\MoySklad\Components\MutationBuilders;

use TotalCRM\MoySklad\Components\MassRequest;
use TotalCRM\MoySklad\Components\Specs\CreationSpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Exceptions\IncompleteCreationFieldsException;

class CreationBuilder extends AbstractMutationBuilder
{
    /**
     * @var CreationSpecs
     */
    protected $specs;

    public function __construct(AbstractEntity &$entity, CreationSpecs &$specs = null)
    {
        parent::__construct($entity);
        if (!$specs) $specs = CreationSpecs::create();
        $this->specs = $specs;
    }

    /**
     * @return AbstractEntity
     * @throws IncompleteCreationFieldsException
     */
    public function execute()
    {
        $this->e->validateFieldsRequiredForCreation();
        $mr = new MassRequest($this->e->getSkladInstance(), $this->e);
        return $mr->create()->get(0);
    }
}
