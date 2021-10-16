<?php

namespace TotalCRM\MoySklad\Entities\Pos;

use TotalCRM\MoySklad\Components\Http\RequestConfig;
use TotalCRM\MoySklad\Interfaces\DoesNotSupportMutationInterface;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

class RetailStore extends PosEntity implements DoesNotSupportMutationInterface
{
    public static $entityName = 'retailstore';

    public static function boot()
    {
        parent::boot();
        static::$customQueryUrl = ApiUrlRegistry::instance()->getPosRetailStoreQueryUrl();
    }

    /**
     * @return \stdClass
     * @throws \Throwable
     */
    public function getAuthToken()
    {
        return $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getPosAttachTokenUrl($this->id),
            null,
            new RequestConfig([
                "usePosApi" => true
            ])
        )->token;
    }
}
