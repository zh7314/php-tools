<?php

include_once './function.php';
include_once './../src/Algorithm/LargeDigitalConversion62.php';
include_once './../src/Ec/OrderKeyGenerator.php';
include_once './../src/Ec/PageTool.php';
include_once './../src/Ec/CheckTimeConflict.php';
include_once './../src/Ec/IP.php';
include_once './../src/Ec/File/MimeTypes.php';
include_once './../src/Ec/File/SecurityCheck.php';
include_once './../src/Ec/Id/GenerateIdCard.php';
include_once './../src/Ec/Id/IdCard.php';
include_once './../src/Ec/Id/IdCardData.php';

use ZX\Algorithm\LargeDigitalConversion62;
use ZX\Ec\OrderKeyGenerator;
use ZX\Ec\PageTool;
use ZX\Ec\CheckTimeConflict;

//生成带前缀+时间序列+随机数的指定长度的订单号
p(OrderKeyGenerator::getRandKey('UD_', 4));
p(OrderKeyGenerator::getRandKey('UD_', 4, false));

//生成带前缀的唯无序订单号
p(OrderKeyGenerator::getShotKey());
p(OrderKeyGenerator::getShotKey('UD_', 4));

$array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
//list($list, $count) = PageTool::pageByArray($array, 3, 3);
$data = PageTool::pageByArray($array, 3, 3);
p($data);


//62进制转换
$res = LargeDigitalConversion62::from10To62(123456);
p($res);
$res = LargeDigitalConversion62::from62To10($res);
p($res);

//测试时间冲突
$time = [];
$is_ok = CheckTimeConflict::check('', '', $time, 1);
if ($is_ok) {
    p('OK');
} else {
    p('NO');
}

//测试身份证








