<?php

namespace TotalCRM\MoySklad\Entities\Reports;

use TotalCRM\MoySklad\Components\FilterQuery;
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
<<<<<<< HEAD
     * @param FilterQuery|null $filter
     * @return \stdClass|string
     * @throws UnknownSpecException
     */
    public static function all(MoySklad $sklad, ?StockReportQuerySpecs $specs = null, ?FilterQuery $filter = null)
    {
        return static::queryWithParam($sklad, 'all', $specs, $filter);
    }

    /**
     * @param MoySklad $sklad
     * @param StockReportQuerySpecs|null $specs
     * @return \stdClass|string
     * @throws UnknownSpecException
     */
    public static function byStore(MoySklad $sklad, ?StockReportQuerySpecs $specs = null, ?FilterQuery $filter = null)
=======
     * @return \stdClass|string
     * @throws UnknownSpecException
     */
    public static function all(MoySklad $sklad, StockReportQuerySpecs $specs = null)
>>>>>>> 4db1a0840be5891584390f55fcaf7eeb9631a8e2
    {
        return static::queryWithParam($sklad, 'bystore', $specs, $filter);
    }

    /**
     * @param MoySklad $sklad
     * @param StockReportQuerySpecs|null $specs
     * @return \stdClass|string
     * @throws UnknownSpecException
     */
<<<<<<< HEAD
    public static function current(MoySklad $sklad, ?StockReportQuerySpecs $specs = null, ?FilterQuery $filter = null)
=======
    public static function byStore(MoySklad $sklad, StockReportQuerySpecs $specs = null)
>>>>>>> 4db1a0840be5891584390f55fcaf7eeb9631a8e2
    {
        return static::queryWithParam($sklad, 'all/current', $specs, $filter);
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
