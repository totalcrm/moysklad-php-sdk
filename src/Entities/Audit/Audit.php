<?php

namespace TotalCRM\MoySklad\Entities\Audit;

use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Components\Query\EntityQuery;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use Exception;

/**
 * Class Audit
 * @package TotalCRM\MoySklad\Entities\Audit
 */
class Audit extends AbstractAudit
{
    public static string $entityName = "audit";
    protected static ?string $customQueryUrl;

    /**
     * @param MoySklad $skladInstance
     * @return string
     * @throws \Throwable
     */
    public static function getFilters(MoySklad &$skladInstance): string
    {
        return (object)$skladInstance->getClient()->get(
            ApiUrlRegistry::instance()->getAuditFiltersUrl()
        );
    }

    /**
     * @param MoySklad $skladInstance
     * @param QuerySpecs|null $querySpecs
     * @return EntityQuery
     * @throws Exception
     */
    public static function query(MoySklad &$skladInstance, QuerySpecs $querySpecs = null)
    {
        return parent::query($skladInstance, $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getAuditUrl());
    }
}
