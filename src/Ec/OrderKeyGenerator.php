<?php

namespace ZX\Ec;

use Exception;

class OrderKeyGenerator
{

    public function __construct()
    {
        return null;
    }

    /*
     * 唯一订单号，并发也基本没问题
     * 无序订单号
     */
    public static function getShotKey(string $prefix = '', int $length = 0)
    {
        return uniqid($prefix) . self::randKeyByLength($length);
    }

    /*
     * 不是绝对唯一，大量并发可能会出现重复
     *时间有序订单号 固定长度
     */
    public static function getRandKey(string $prefix = '', int $length = 0, bool $is_repeat = false)
    {
        list($usec, $sec) = explode(" ", microtime());

        list($zero, $u) = explode('.', $usec);
        $time = date('YmdHis', $sec) . $u;

        //随机数
        $randval = '';
        if ($is_repeat) {
            $randval = self::randKeyByLength($length) . self::randKeyByLength($length);
        } else {
            $randval = self::randKeyByLength($length);
        }
        return $prefix . $time . $randval;
    }

    public static function randKeyByLength(int $length = 0)
    {
        if ($length <= 0) {
            return null;
        }
        list($strt, $end) = OrderKeyGenerator::getRandRange($length);

        return rand($strt, $end);
    }

    public static function getRandRange(int $length = 0)
    {
        if ($length <= 0) {
            return null;
        }
        $strt = bcpow(10, $length);
        $end = bcsub(bcpow(10, $length + 1), 1);

        return [$strt, $end];
    }
}
