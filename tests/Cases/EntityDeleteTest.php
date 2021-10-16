<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Components\Http\RequestLog;
use TotalCRM\MoySklad\Entities\Products\Product;
use TotalCRM\MoySklad\Exceptions\ApiResponseException;
use TotalCRM\MoySklad\Exceptions\RequestFailedException;

require_once "TestCase.php";

class EntityDeleteTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testProductDeletion(){
        $this->methodStart();
        $product = (new Product($this->sklad, [
            "name" => "TestProduct"
        ]))->buildCreation()->execute();
        $product = Product::query($this->sklad)->byId($product->id);
        $this->assertTrue(!empty($product->id));
        $this->say("Created product with id: " . $product->id);
        $this->assertTrue($product->delete() === true);
        $this->say("Deleted");
        $this->expectException(ApiResponseException::class);
        $product = Product::query($this->sklad)->byId($product->id);
        $this->methodEnd();
    }
}
