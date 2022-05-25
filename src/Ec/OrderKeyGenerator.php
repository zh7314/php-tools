<?php

namespace ZX\Ec;

use SplFileObject;
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
    public static function getShotKey(string $prefix = '')
    {
        return uniqid($prefix) . rand(10, 99);
    }

    /*
     * 不是绝对唯一，大量并发可能会出现重复
     *时间有序订单号
     */
    public static function getRandKey(string $prefix = '')
    {
        list($usec, $sec) = explode(" ", microtime());

        list($zero, $u) = explode('.', $usec);
        $time = date('YmdHis', $sec) . $u;
        //随机数
        $randval = rand(100, 999) . rand(10, 99);
        return $prefix . $time . $randval;
    }

    public static function randKeyByLength(int $length = 0)
    {
        if ($length <= 0) {
            throw new Exception('The length of random number generation cannot be less than or equal to 0');
        }


    }

    public static function getRandRange(int $length = 0)
    {
        if ($length <= 0) {
            throw new Exception('The length of random number generation cannot be less than or equal to 0');
        }
        $strt = 0;
        $end = 0;
        $strt = 10*$length;


    }
}
