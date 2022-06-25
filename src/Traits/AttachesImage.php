<?php

namespace TotalCRM\MoySklad\Traits;

use TotalCRM\MoySklad\Components\Fields\ImageField;
use TotalCRM\MoySklad\Entities\AbstractEntity;

trait AttachesImage
{
    /**
     * @param ImageField $imageField
     */
    public function attachImage(ImageField $imageField): void
    {
        /**  @var AbstractEntity $this */
        $this->fields->image = $imageField;
    }
}
