<?php

namespace TotalCRM\MoySklad;

use TotalCRM\MoySklad\Components\Http\MoySkladHttpClient;
use TotalCRM\MoySklad\Registers\EntityRegistry;

class MoySklad
{

    private MoySkladHttpClient $client;
    private string $hashCode;
    private static array $instances = [];

    private function __construct($login, $password, $posToken, $hashCode, $subdomain = "api")
    {
        $this->client = new MoySkladHttpClient($login, $password, $posToken, $subdomain);
        $this->hashCode = $hashCode;
    }

    /**
     * Get hashcode for given login/password
     * @param $login
     * @param $password
     * @return string
     */
    private static function makeHash($login, $password): string
    {
        return crc32($login . $password);
    }

    /**
     * Use it instead of constructor
     * @param $login
     * @param $password
     * @param string $subdomain
     * @param $posToken
     * @return MoySklad
     */
    public static function getInstance($login, $password, $subdomain = "api", $posToken = null): MoySklad
    {
        $hash = static::makeHash($login, $password);
        if (empty(static::$instances[$hash])) {
            static::$instances[$hash] = new static($login, $password, $posToken, $hash, $subdomain);
            EntityRegistry::instance()->bootEntities();
        }
        return static::$instances[$hash];
    }

    /**
     * Get instance with given hashcode
     * @param $hashCode
     * @return MoySklad
     */
    public static function findInstanceByHash($hashCode): MoySklad
    {
        return static::$instances[$hashCode];
    }

    /**
     * We're java now
     * @return string
     */
    public function hashCode(): string
    {
        return $this->hashCode;
    }

    /**
     * @return MoySkladHttpClient
     */
    public function getClient(): MoySkladHttpClient
    {
        return $this->client;
    }

    /**
     * @param $posToken
     * @deprecated
     */
    public function setPosToken($posToken): void
    {
        $this->client->setPosToken($posToken);
    }
}
