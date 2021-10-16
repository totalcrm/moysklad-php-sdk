<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Components\FilterQuery;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\Assortment;
use TotalCRM\MoySklad\Entities\Counterparty;
use TotalCRM\MoySklad\Entities\Documents\Movements\Enter;
use TotalCRM\MoySklad\Entities\Documents\Orders\CustomerOrder;
use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Products\Product;
use TotalCRM\MoySklad\Entities\Store;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class CustomerOrderAffectsStockTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCustomerOrderAffectsStock(){
        $this->methodStart();

        $testProductName = $this->makeName("TestProduct");
        $testCounterpartyName = $this->makeName('TestCounterparty');
        $testEnterName = $this->makeName("TestEnter");

        $org = Organization::query($this->sklad)->getList()->get(0);
        $store = Store::query($this->sklad)->getList()->get(0);

        $cp = (new Counterparty($this->sklad, [
            "name" => $testCounterpartyName
        ]))->buildCreation()->execute();
        $this->say("Cp id:" . $cp->id);

        $product = (new Product($this->sklad, [
            "name" => $testProductName,
            "quantity" => 25
        ]))->buildCreation()->execute();
        $this->say("Product id:" . $product->id);

        $positionList = new EntityList($this->sklad);
        $positionList->push($product);

        $enter = (new Enter($this->sklad, [
           "name" => $testEnterName
        ]))->buildCreation()->
            addOrganization($org)->
            addStore($store)->
            addPositionList($positionList)->
            execute();
        $this->say("Enter id:" . $enter->id );

        $filteredProduct = Assortment::query($this->sklad,QuerySpecs::create([
            "maxResults" => 1
        ]))->filter(
            (new FilterQuery())->eq("name", $testProductName)
        )->transformItemsToMetaClass()[0];
        $this->assertTrue($filteredProduct->id === $product->id);

        $co = (new CustomerOrder($this->sklad, [
            "name" => "TestOrder"
        ]))
            ->buildCreation()
            ->addCounterparty($cp)
            ->addOrganization($org)
            ->addPositionList(new EntityList($this->sklad, [$product]))
            ->execute();

        $this->say("Order id:" . $co->id );

        $enter->delete();
        $co->delete();
        $cp->delete();
        $product->delete();

        $this->methodEnd();
    }
}
