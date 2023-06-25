<?php

namespace ZX\Tools;

use Exception;
use Iterator;

/*
 * 内存分页工具
 */

class PageTool
{

    public static function pageByIterator(Iterator $data, bool $preserve_keys = true, int $page = 1, int $pageSize = 10)
    {
        return self::pageByArray(iterator_to_array($data, $preserve_keys), $page, $pageSize);
    }

    public static function pageByArray(array $data, int $page = 1, int $pageSize = 10)
    {
        if (empty($data)) {
            throw new Exception("data can't be null");
        }
        return ['list' => array_slice($data, ($page - 1) * $pageSize, $pageSize), 'count' => count($data)];
    }
}
