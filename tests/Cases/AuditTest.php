<?php

namespace Tests\Cases;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\Audit\Audit;
use TotalCRM\MoySklad\Entities\Audit\AuditEvent;
use TotalCRM\MoySklad\Entities\Documents\Orders\CustomerOrder;
use TotalCRM\MoySklad\Lists\RelationEntityList;

require_once "TestCase.php";

class AuditTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAuditsList(){
        $this->methodStart();
        $audits = Audit::query($this->sklad, QuerySpecs::create([
            'maxResults' => 3
        ]))->getList();
        /**
         * @var RelationEntityList $events
         */
        $audit = $audits->get(0);
        $this->assertInstanceOf(Audit::class, $audit);
        $events = $audit->getAuditEvents();
        $this->assertInstanceOf(AuditEvent::class, $events->get(0));
        Audit::getFilters($this->sklad);
        $this->methodEnd();
    }
}
