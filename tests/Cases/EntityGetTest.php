<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Components\Expand;
use TotalCRM\MoySklad\Components\FilterQuery;
use TotalCRM\MoySklad\Components\Http\RequestLog;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Assortment;
use TotalCRM\MoySklad\Entities\Documents\Movements\Demand;
use TotalCRM\MoySklad\Entities\Employee;
use TotalCRM\MoySklad\Entities\Folders\ProductFolder;
use TotalCRM\MoySklad\Entities\Group;
use TotalCRM\MoySklad\Entities\Products\Product;
use TotalCRM\MoySklad\Entities\Products\Service;
use TotalCRM\MoySklad\Lists\EntityList;

require_once "TestCase.php";

class EntityGetTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGetProductList(){
        $this->methodStart();
        $this->say("Start getting products");
        $this->timeStart();
        $productList = Product::query($this->sklad, QuerySpecs::create([
            'maxResults' => 25
        ]))->getList();
        $this->say("Took " . $this->timeEnd() . " sec");
        $this->assertTrue(
            $productList[0] instanceof Product
        );

        $this->say("Start getting assortment");
        $this->timeStart();
        $assortmentList = Assortment::query($this->sklad, QuerySpecs::create([
            'maxResults' => 25
        ]))->getList();
        $this->say("Took " . $this->timeEnd() . " sec");
        $this->say("Start transform, have " . $assortmentList->count() . " items\n");
        $this->timeStart();
        $assortmentList->transformItemsToMetaClass()
            ->each(function(AbstractEntity $e){
                $this->assertTrue(
                    $e instanceof Product ||
                    $e instanceof Service
                );
            });
        echo "Took " . $this->timeEnd() . " sec.";
        $this->methodEnd();
        return $productList;
    }

    /**
     * @depends testGetProductList
     * @param EntityList $productList
     */
    public function testProductRelations(EntityList $productList){
        $this->methodStart();
        $product = $productList[0];
        $this->assertTrue(
            $product->relations->group instanceof Group
        );
        $this->methodEnd();
    }

    /**
     * @depends testGetProductList
     * @param EntityList $productList
     */
    public function testEntityRefresh(EntityList $productList){
        $this->methodStart();
        /**
         * @var Product $product
         */
        $product = $productList[0];
        $this->assertEquals(
            $product->id,
            $product->fresh()->id
        );
        $this->methodEnd();
    }

    public function testGetProductListWithExpand(){
        $this->methodStart();
        $products = Product::query($this->sklad, QuerySpecs::create([
            'maxResults' => 5
        ]))->withExpand(Expand::create(['owner']))->getList()->each(function(Product $p){
           $this->assertNotNull(
               $p->relations->find(Employee::class)->id
           );
        });
        $this->methodEnd();
    }

    public function testGetProductWithExpand(){
        $this->methodStart();
        $someProduct = Product::query($this->sklad, QuerySpecs::create(['maxResults' => 1]))->getList()->get(0);
        $sameProduct = Product::query($this->sklad)
            ->withExpand(Expand::create(['owner']))
            ->byId($someProduct->id);
        $this->assertNotNull(
            $sameProduct->relations->find(Employee::class)->id
        );
        $this->methodEnd();
    }
}
