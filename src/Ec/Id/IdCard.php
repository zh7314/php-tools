<?php

namespace ZX\Ec\Id;

class IdCard
{

    // 老身份证15位
    private $idTypeOld = 15;
    // 新身份证18位
    private $idTypeNew = 18;
    // 有效长度
    private $effectiveLength = 17;
    //加权因子
    private $wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
    //校验码串
    private static $ai = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
    //身份证年
    private $year;
    //身份证月
    private $month;
    //身份证天
    private $day;

    /**
     * 身份证性别
     * 奇数为男性，偶数为女性
     * 1为男性 0为女性
     */
    private $sex;
    //生日日期
    private $birthday;
    //出生日期
    private $bornDay;
    //身份证号码
    private $idNumber;
    //省
    private $province;
    //市
    private $city;
    //区
    private $area;
    //省名称
    private $provinceName;
    //市名称
    private $cityName;
    //区名称
    private $areaName;
    //派出所编号
    private $policeStation;

    public function __construct(string $idNumber = null)
    {
        if (empty($idNumber)) {
            throw new \Exception('身份证号不能为空');
        }
        //去除空格，全部转成大写，防止X和x问题
        $idNumber = strtoupper(trim($idNumber));
        if (mb_strlen($idNumber) == self::$idTypeOld) {
            $idNumber = $this->idNumber15To18($idNumber);
        }
        if (mb_strlen($idNumber) != self::$idTypeNew) {
            throw new \Exception("身份证号长度不是18位");
        }

        $this->idNumber = $idNumber;
        $this->parseIdNumber($this->idNumber);
    }

    /**
     *
     * 判断18位身份证的合法性
     *
     * 根据〖中华人民共和国国家标准GB11643-1999〗中有关公民身份号码的规定，公民身份号码是特征组合码，由十七位数字本体码和一位数字校验码组成。
     * 排列顺序从左至右依次为：六位数字地址码，八位数字出生日期码，三位数字顺序码和一位数字校验码。
     *
     * 顺序码: 表示在同一地址码所标识的区域范围内，对同年、同月、同 日出生的人编定的顺序号，顺序码的奇数分配给男性，偶数分配 给女性。
     *
     * 1.前1、2位数字表示：所在省份的代码； 2.第3、4位数字表示：所在城市的代码； 3.第5、6位数字表示：所在区县的代码；
     * 4.第7~14位数字表示：出生年、月、日； 5.第15、16位数字表示：所在地的派出所的代码； 6.第17位数字表示性别：奇数表示男性，偶数表示女性；
     * 7.第18位数字是校检码：也有的说是个人信息码，一般是随计算机的随机产生，用来检验身份证的正确性。校检码可以是0~9的数字，有时也用x表示。
     * 第十八位数字(校验码)的计算方法为： 1.将前面的身份证号码17位数分别乘以不同的系数。从第一位到第十七位的系数分别为：7 9 10 5 8 4 2 1
     * 6 3 7 9 10 5 8 4 2
     *
     * 2.将这17位数字和系数相乘的结果相加。
     * 3.用加出来和除以11，看余数是多少
     * 4.余数只可能有0 1 2 3 4 5 6 7 8 9 10这11个数字。其分别对应的最后一位身份证的号码为1 0 X 9 8 7 6 5 4 3 2。
     * 5.通过上面得知如果余数是2，就会在身份证的第18位数字上出现罗马数字的Ⅹ。如果余数是10，身份证的最后一位号码就是2。
     *
     * 验证身份证
     */
    public function isIdNumberValid(): bool
    {

        $idNumber = $this->idNumber;
        //按照序号从校验码串中提取相应的字符。
        $check_number = $this->getCheckCode($idNumber);

        //返回
        if ($check_number == $idNumber[self::$effectiveLength]) {
            return true;
        } else {
            return false;
        }
    }

    protected function getCheckCode(string $idNumber)
    {

        $sigma = 0;
        //按顺序循环处理前17位
        for ($i = 0; $i < self::$effectiveLength; $i++) {
            //提取前17位的其中一位，并将变量类型转为实数
            $b = (int)$idNumber[$i];
            //提取相应的加权因子
            $w = self::$wi[$i];
            //把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }
        //计算序号
        $number = $sigma % 11;
        //按照序号从校验码串中提取相应的字符。
        $check_number = self::$ai[$number];
        return $check_number;
    }

    //解析身份证号
    protected function parseIdNumber(string $idNumber = null)
    {

        $this->province = mb_substr($idNumber, 0, 2);
        $this->city = mb_substr($idNumber, 2, 2);
        $this->area = mb_substr($idNumber, 4, 2);
        //出生日期
        $this->bornDay = mb_substr($idNumber, 6, 8);
        //生日日期
        $this->birthday = mb_substr($this->bornDay, 4, 4);
        $this->year = mb_substr($this->bornDay, 0, 4);
        $this->month = mb_substr($this->bornDay, 4, 2);
        $this->day = mb_substr($this->bornDay, 6, 2);

        $this->policeStation = mb_substr($idNumber, 14, 2);

        $sex = mb_substr($idNumber, 16, 1);

        $this->sex = $sex % 2;
        //校验码
        $x = mb_substr($idNumber, 17, 1);
    }

    //获取转码18位的身份证号
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    //获取性别
    public function getSex()
    {
        return $this->sex;
    }

    //获取年
    public function getYear()
    {
        return $this->year;
    }

    //获取月
    public function getMonth()
    {
        return $this->month;
    }

    //获取日
    public function getDay()
    {
        return $this->day;
    }

    //获取生日日期
    public function getBirthday()
    {
        return $this->birthday;
    }

    //获取出生日期
    public function getBornDay()
    {
        return $this->bornDay;
    }

    /**
     * 身份证15位转18位
     * 第一代身份证：15位身份证号码的意义
     *
     * 1-2位省、自治区、直辖市代码；
     * 3-4位地级市、盟、自治州代码；
     * 5-6位县、县级市、区代码；
     * 7-12位出生年月日,比如670401代表1967年4月1日,这是和18位号码的第一个区别；
     * 13-15位为顺序号，其中15位男为单数，女为双数；
     * 与18位身份证号的第二个区别：没有最后一位的校验码。
     *
     * 第二代身份证：18位身份证号码的意义
     *
     * 　　①前1、2位数字表示：所在省份的代码，河南的省份代码是41哦!
     * 　　②第3、4位数字表示：所在城市的代码;
     * 　　③第5、6位数字表示：所在区县的代码;
     * 　　④第7~14位数字表示：出生年、月、日;
     * 　　⑤第15、16位数字表示：所在地的派出所的代码;
     * 　　⑥第17位数字表示性别：奇数表示男性，偶数表示女性;
     * 　　⑦第18位数字是校检码：也有的说是个人信息码，一般是随计算机随机产生，用来检验身份证的正确性。校检码可以是0~9的数字，有时也用x表示。
     */
    protected function idNumber15To18(string $idNumber = null)
    {
        if (mb_strlen($idNumber) != self::$idTypeOld) {
            throw new \Exception('待转换的身份证不是15位');
        }
        $code = $idNumber;
        /*
         *  如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
         * 这里有三种情况，百岁18XX，19XX，20XX，防止有些特殊情况20XX的也是15位，就吧20的也加上，防止万一情况
         */
        if (array_search(substr($code, 12, 3), array('996', '997', '998', '999')) !== false) {
            $idNumber = mb_substr($code, 0, 6) . '18' . mb_substr($code, 6, 9);
        } else {
            if (intval(substr($code, 6, 2)) <= 17) {
                $idNumber = mb_substr($code, 0, 6) . '20' . mb_substr($code, 6, 9);
            } else {
                $idNumber = mb_substr($code, 0, 6) . '19' . mb_substr($code, 6, 9);
            }
        }

        $check_number = $this->getCheckCode($idNumber);

        $this->idNumber = $idNumber . $check_number;
        return $this->idNumber;
    }

}
