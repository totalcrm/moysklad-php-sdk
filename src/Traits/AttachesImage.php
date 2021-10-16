<?php

namespace TotalCRM\MoySklad\Traits;

use TotalCRM\MoySklad\Components\Fields\ImageField;
use TotalCRM\MoySklad\Entities\AbstractEntity;

trait AttachesImage
{
    public function attachImage(ImageField $imageField)
    {
        /**
         * @var AbstractEntity $this
         */
        $this->fields->image = $imageField;
    }
}
