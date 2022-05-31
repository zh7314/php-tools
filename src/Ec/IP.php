<?php

namespace ZX\Ec;

class IP
{
    /*
     * 判断是否是合法的IPv4 IP地址
     */
    public static function checkIpv4(string $ip)
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

    /*
     * 判断是否是合法的IPv4 IPv6
     */
    public static function checkIp(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /*
     * 判断是否是合法的IPv4 IP地址
     */
    public static function isIpv4(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }


    /*
     * 判断是否是合法的公共IPv4地址，192.168.1.1这类的私有IP地址将会排除在外
     */

    public static function isPublicNetIpv4(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE);
    }


    /*
     * 判断是否是合法的IPv6地址
     */
    public static function isPublicNetIpv6(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE);

    }


    /*
     * 判断是否是public IPv4 IP或者是合法的Public IPv6 IP地址
     */

    public static function isPublicNetIpv61(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

}