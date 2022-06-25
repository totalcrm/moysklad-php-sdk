<?php

namespace TotalCRM\MoySklad\Components\Http;

/**
 * Class RequestLog
 * @package TotalCRM\MoySklad\Components\Http
 */
abstract class RequestLog
{
    private static bool $enabled = true;
    private static int $storageSize = 50;
    private static int $total = 0;
    private static array $history = [];

    /**
     * @param $size
     */
    public static function setStorageSize($size): void
    {
        if (is_int($size)) {
            self::$storageSize = $size;
        }
    }

    /**
     * @param $row
     */
    public static function add($row): void
    {
        if (!self::$enabled) {
            return;
        }

        self::$total++;
        self::$history[] = $row;

        if (self::$storageSize !== 0 && count(self::$history) > self::$storageSize) {
            array_shift(self::$history);
        }
    }

    /**
     * @param $row
     */
    public static function replaceLast($row): void
    {
        if (!self::$enabled) {
            return;
        }

        self::$history[count(self::$history) - 1] = $row;
    }

    /**
     * @return mixed|null
     */
    public static function getLast()
    {
        $idx = count(self::$history) - 1;
        if ($idx >= 0) {
            return self::$history[$idx];
        }

        return null;
    }

    /**
     * @return array
     */
    public static function getRequestList(): array
    {
        return array_map(static function ($row) {
            return $row['req'];
        }, self::$history);
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            "history" => self::$history,
            "total" => self::$total
        ];
    }

    /**
     * Stop log collecting
     * @param bool $enabled
     * @return void
     */
    public static function setEnabled($enabled): void
    {
        self::$enabled = $enabled;
    }
}
