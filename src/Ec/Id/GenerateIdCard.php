<?php

namespace ZX\Ec\Id;

use ZX\Ec\Id\IdCardData;

class GenerateIdCard
{

    /**
     * 批量生产身份证号18位
     * @param type $province
     * @param type $city
     * @param type $area
     * @param type $sex
     * @param type $bornDay 出生日期
     * @param type $number 批量生产的数量
     */
    public function massProductionIdNumber18($province, $city, $area, $sex, $bornDay, $number)
    {

    }

    /**
     * 批量生产身份证号1位
     * @param type $province
     * @param type $city
     * @param type $area
     * @param type $sex
     * @param type $bornDay 出生日期
     * @param type $number 批量生产的数量
     */
    public function massProductionIdNumber15($province, $city, $area, $sex, $bornDay, $number)
    {

    }

    //区域编码直接生产前6位随机省市区
    protected function getRandomAreaCode()
    {

        $data = IdCardData::getAllData();
        $data = json_decode($data, true);
        $code = '';

        $tempProvince = [];
        foreach ($data as $k => $v) {
            $tempProvince[] = $tempProvince;
        }

        $randomProvince = random_int(0, count($tempProvince) - 1);
        $province = $data[$randomProvince];

        if ($province['total'] == 0) {
            $code = $province['region_code'];
            return $code;
        }

        $randomCity = random_int(0, $province['total'] - 1);
        $city = $province['children'][$randomCity];

        if ($city['total'] == 0) {
            $code = $city['region_code'];
            return $code;
        }

        $randomArea = random_int(0, $city['total'] - 1);
        $area = $city['children'][$randomArea];

        return $area['region_code'];
    }

    /**
     * 获取随机出生日期
     * 1970 年 1 月 1 日08:00:00开始时间
     * $type true 18位 false 15位
     */
    protected function getRandomBornDay(bool $type = true)
    {
        $start = strtotime('1970-01-01 08:00:00');
        $end = time();
        $randomBornDay = random_int($start, $end);
        if ($type) {
            $bornDayCode = date('Ymd', $randomBornDay);
        } else {
            $bornDayCode = date('ymd', $randomBornDay);
        }
        return $bornDayCode;
    }

    //获取随机性别 偶数是女性 基数是男性
    protected function getRandomSex()
    {
        return random_int(1, 9);
    }

    protected function generateBase18()
    {
        $suffix_a = mt_rand(0, 9);
        $suffix_b = mt_rand(0, 9);

        $areaCode = self::getRandomAreaCode();
        $bornDay = self::getRandomBornDay();
        $sex = self::getRandomSex();

        $base = $areaCode . $bornDay . $suffix_a . $suffix_b . $sex;
        return $base;
    }

    protected function generateBase15()
    {
        $suffix_a = mt_rand(0, 9);
        $suffix_b = mt_rand(0, 9);

        $areaCode = self::getRandomAreaCode();
        $bornDay = self::getRandomBornDay(false);
        $sex = self::getRandomSex();

        $base = $areaCode . $bornDay . $suffix_a . $suffix_b . $sex;
        return $base;
    }

    public function generateID15(int $number = 1)
    {
        $array = [];
        for ($i = 0; $i < $number; $i++) {
            $base = self::generateBase15();
//            $idNumber = $base . self::calcSuffixD($base);
            $array[] = $base;
        }

        return $array;
    }

    public function generateID(int $number = 1)
    {
        $array = [];
        for ($i = 0; $i < $number; $i++) {
            $base = self::generateBase18();
            $idNumber = $base . self::calcSuffixD($base);
            $array[] = $idNumber;
        }

        return $array;
    }

    protected function calcSuffixD($base)
    {
        if (strlen($base) <> 17) {
            die('Invalid Length');
        }
        // 权重
        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $sums = 0;
        for ($i = 0; $i < 17; $i++) {
            $sums += substr($base, $i, 1) * $factor[$i];
        }

        $mods = $sums % 11; //10X98765432

        switch ($mods) {
            case 0:
                return '1';
                break;
            case 1:
                return '0';
                break;
            case 2:
                return 'X';
                break;
            case 3:
                return '9';
                break;
            case 4:
                return '8';
                break;
            case 5:
                return '7';
                break;
            case 6:
                return '6';
                break;
            case 7:
                return '5';
                break;
            case 8:
                return '4';
                break;
            case 9:
                return '3';
                break;
            case 10:
                return '2';
                break;
        }
    }

}
