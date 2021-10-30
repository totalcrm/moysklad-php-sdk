<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Exceptions\UnknownSpecException;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Components\Specs\EmptySpecs;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;
use Throwable;
use stdClass;

abstract class AbstractReport extends AbstractEntity implements DoesNotSupportMutationInterface
{
    public static string $entityName = 'report';
    public static string $reportName = 'a_report';

    /**
     * @param MoySklad $sklad
     * @param null $param
     * @param QuerySpecs|null $specs
     * @return stdClass|string
     * @throws UnknownSpecException
     */
    protected static function queryWithParam(MoySklad $sklad, $param = null, QuerySpecs $specs = null)
    {
        if (!$specs) {
            $specs = EmptySpecs::create();
        }

        /** @var ApiUrlRegistry $apiUrlRegistryInstance */
        $apiUrlRegistryInstance = ApiUrlRegistry::instance();
        if ($param === null) {
            $url = $apiUrlRegistryInstance->getReportUrl(static::$reportName);
        } else {
            $url = $apiUrlRegistryInstance->getReportWithParamUrl(static::$reportName, $param);
        }

        try {
            return $sklad->getClient()->get($url, $specs->toArray());
        } catch (Throwable $e) {
        }
    }
}
