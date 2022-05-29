<?php

namespace ZX\Ec;

class IP
{

    public static function checkIp(string $ip)
    {
        $arr = explode('.', $ip);
        if (count($arr) != 4) {
            return false;
        } else {
            for ($i = 0; $i < 4; $i++) {
                if (($arr[$i] < '0') || ($arr[$i] > '255')) {
                    return false;
                }
            }
        }
        return true;
    }
}