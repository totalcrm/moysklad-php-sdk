<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Entities\Counterparty;
use TotalCRM\MoySklad\Exceptions\Relations\RelationDoesNotExistException;
use TotalCRM\MoySklad\Lists\RelationEntityList;

require_once "TestCase.php";

class GetRelationsTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws RelationDoesNotExistException
     * @throws \MoySklad\Exceptions\Relations\RelationIsList
     * @throws \MoySklad\Exceptions\Relations\RelationIsSingle
     * @throws \MoySklad\Exceptions\UnknownEntityException
     */
    public function testGetSingleRelation(){
        $cp = Counterparty::query($this->sklad)->getList()->get(0);
        $accounts = $cp->relations->listQuery('accounts')->getList();
        $this->assertInstanceOf(RelationEntityList::class, $accounts);
        $owner = $cp->relations->fresh('owner');
        $this->assertTrue(!empty($owner->id));
        $this->expectException(RelationDoesNotExistException::class);
        $cp->relations->fresh('undefined');
    }
}
