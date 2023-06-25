# php-tools

#### 安装
```
composer require zx/php-tools
```

#### 介绍
PHP常用工具类代码集合，方便开发各种系统，减少开发难度，加速开发过程

#### 现有组件
- [x] 大数值62进制互转
- [x] 时间段冲突检测
- [x] 身份证号正确性检测
- [x] IPV4 IPV6正确性检测
- [x] 订单号生成
- [x] 数组，迭代器分页工具
- [x] 上传图片安全检测

#### todo
后续会增加更多常用组件

- [ ] 


#### demo演示
```
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
pp($res);

//测试时间冲突
$time = [];
$is_ok = CheckTimeConflict::check('', '', $time, 1);
if ($is_ok) {
    p('OK');
} else {
    p('NO');
}

```
