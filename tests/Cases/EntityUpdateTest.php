<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Assortment;
use TotalCRM\MoySklad\Entities\Group;
use TotalCRM\MoySklad\Entities\Products\Product;
use TotalCRM\MoySklad\Entities\Products\Service;
use TotalCRM\MoySklad\Lists\EntityList;

require_once "TestCase.php";

class EntityUpdateTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testSingleUpdate(){
        $this->methodStart();
        /**
         * @var Product $pl
         */
        $pl = Product::query($this->sklad)->getList()[0];
        $oldName = $pl->name;
        $newName = $this->faker->linuxProcessor;
        $pl->name = $newName;
        $uProd = $pl->buildUpdate()->execute();
        $this->assertNotEquals(
            $oldName,
            $uProd->name
        );
        $pl->name = $oldName;
        $pl->buildUpdate()->execute();
        $this->methodEnd();
    }
}
