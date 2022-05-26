<?php

namespace ZX\Ec;

use Exception;

class OrderKeyGenerator
{
    /*
     * 唯一订单号，并发也基本没问题
     * 无序订单号
     */
    public function getShotKey(string $prefix = '', int $length = 0)
    {
        return uniqid($prefix) . $this->randKeyByLength($length);
    }

    /*
     * 不是绝对唯一，大量并发可能会出现重复
     *时间有序订单号 固定长度
     */
    public function getRandKey(string $prefix = '', int $length = 0, bool $is_repeat = false)
    {
        list($usec, $sec) = explode(" ", microtime());
        list($zero, $u) = explode('.', $usec);

        $time = date('YmdHis', $sec) . $u;

        //随机数
        $randval = '';
        if ($is_repeat) {
            $randval = $this->randKeyByLength($length) . $this->randKeyByLength($length);
        } else {
            $randval = $this->randKeyByLength($length);
        }
        return $prefix . $time . $randval;
    }

    public function randKeyByLength(int $length = 0)
    {
        if ($length <= 0) {
            return null;
        }
        list($strt, $end) = $this->getRandRange($length);

        return rand($strt, $end);
    }

    public function getRandRange(int $length = 0)
    {
        if ($length <= 0) {
            return null;
        }
        $strt = bcpow(10, $length);
        $end = bcsub(bcpow(10, $length + 1), 1);

        return compact($strt, $end);
    }
}
