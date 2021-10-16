<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Components\Http\RequestLog;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Assortment;
use TotalCRM\MoySklad\Entities\Employee;
use TotalCRM\MoySklad\Entities\Group;
use TotalCRM\MoySklad\Entities\Pos\RetailStore;
use TotalCRM\MoySklad\Entities\Products\Product;
use TotalCRM\MoySklad\Entities\Products\Service;
use TotalCRM\MoySklad\Lists\EntityList;

require_once "TestCase.php";

class PosTest extends TestCase{
    /**
     * @throws \Throwable
     */
//    public function testRetailStore(){
////        $retails = RetailStore::query($this->sklad)->getList();
////        if ( $retails->count() ){
////            /**
////             * @var RetailStore $retail
////             */
////            $retail = $retails->get(0);
////            $token = $retail->getAuthToken();
////            $this->assertNotNull($token);
////        }
//    }
}
