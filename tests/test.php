<?php

include_once './../src/Ec/OrderKeyGenerator.php';
include_once './function.php';

use ZX\Ec\OrderKeyGenerator;
use ZX\Algorithm\LargeDigitalConversion62;

//生成

//生成带前缀+时间序列+随机数的指定长度的订单号
p(OrderKeyGenerator::getRandKey('UD_', 4));
p(OrderKeyGenerator::getRandKey('UD_', 4, false));


//生成带前缀的唯无序订单号
p(OrderKeyGenerator::getShotKey());
p(OrderKeyGenerator::getShotKey('UD_', 4));



