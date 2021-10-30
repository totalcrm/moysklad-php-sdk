<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Components\Specs\QuerySpecs\Reports\StockReportQuerySpecs;
use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Exceptions\UnknownSpecException;
use TotalCRM\MoySklad\MoySklad;

class StockReport extends AbstractReport
{
    public static string $reportName = 'stock';

    /**
     * @param MoySklad $sklad
     * @param StockReportQuerySpecs|null $specs
     * @return \stdClass|string
     * @throws UnknownSpecException
     */
    public static function all(MoySklad $sklad, StockReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'all', $specs);
    }

    /**
     * @param MoySklad $sklad
     * @param StockReportQuerySpecs|null $specs
     * @return \stdClass|string
     * @throws UnknownSpecException
     */
    public static function byStore(MoySklad $sklad, StockReportQuerySpecs $specs = null)
    {
        return static::queryWithParam($sklad, 'bystore', $specs);
    }

    /**
     * @param MoySklad $sklad
     * @param AbstractDocument $operation
     * @return \stdClass
     * @throws UnknownSpecException
     */
    public static function byOperation(MoySklad $sklad, AbstractDocument $operation)
    {
        return static::queryWithParam($sklad, 'byoperation', StockReportQuerySpecs::create([
            'operation.id' => $operation->getMeta()->getId()
        ]));
    }
}
