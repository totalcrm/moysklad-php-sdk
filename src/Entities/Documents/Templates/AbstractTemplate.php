<?php

namespace TotalCRM\MoySklad\Entities\Documents\Templates;

use TotalCRM\MoySklad\Entities\AbstractEntity;

class AbstractTemplate extends AbstractEntity
{
    public static string $entityName = 'a_template';

    public function getContent()
    {
        return $this->fields->content;
    }
}
