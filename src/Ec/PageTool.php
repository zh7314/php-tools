<?php

namespace ZX\Ec;

use Exception;
use Iterator;

/*
 * 内存分页工具
 */

class PageTool
{

    public static function page(Iterator|array $data, int $page = 1, int $pageSize = 10)
    {
        if ($data instanceof Iterator) {
            self::pageByIterator($data, $page, $pageSize);
        } elseif (is_array($data)) {
            self::pageByArray($data, $page, $pageSize);
        } else {
            throw new Exception('type error');
        }
    }

    //计算获取数据的起始
    public static function getCount(int $count, int $page = 1, int $pageSize = 10)
    {
        $start = 0;
        $end = 0;

        $start = ($page - 1) * $pageSize;

        $end = min(($page * $pageSize), $count);
    }

    public static function pageByIterator(Iterator $data, int $page = 1, int $pageSize = 10)
    {
        print_r($data);
        p(__METHOD__);


    }

    public static function pageByArray(array $data, int $page = 1, int $pageSize = 10)
    {
        print_r($data);
        p(__METHOD__);


    }
}
