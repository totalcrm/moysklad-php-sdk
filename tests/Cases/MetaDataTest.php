<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Entities\Documents\Orders\CustomerOrder;
use TotalCRM\MoySklad\Entities\Misc\Attribute;
use TotalCRM\MoySklad\Entities\Misc\State;
use TotalCRM\MoySklad\Lists\EntityList;

require_once "TestCase.php";

class MetaDataTest extends TestCase{
    public function testOrderMetadata(){
        $this->methodStart();
        $meta = CustomerOrder::getMetaData($this->sklad);
        $this->assertTrue($meta->attributes instanceof EntityList);
        $this->assertTrue($meta->states instanceof EntityList);
        if ( $meta->attributes->offsetExists(0) ) {
            $this->assertTrue($meta->attributes[0] instanceof Attribute);
        }
        if ( $meta->states->offsetExists(0) ) {
            $this->assertTrue($meta->states[0] instanceof State);
        }
        $this->methodEnd();
    }
}
