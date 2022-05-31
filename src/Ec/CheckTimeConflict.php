<?php

namespace ZX\Ec;

use Exception;

class CheckTimeConflict
{
    /**
     * 检查活动时间段冲突
     * @param string|null $start_time 活动开始时间
     * @param string|null $end_time 活动结束时间
     * @param array $array 待检查所有数据的数组集合，注意少一些字段，这样检查的性能会更好，必要字段就是[[id,start_time,end_time],[id,start_time,end_time]]
     * @param null $id 编辑时，待检查的id
     * @return bool
     * @throws Exception
     */
    public static function check(string $start_time = null, string $end_time = null, array $array = [], $id = null)
    {
        if (empty($start_time)) {
            throw new Exception('开始时间不能为空');
        }
        if (empty($end_time)) {
            throw new Exception('结束时间不能为空');
        }
        if (strtotime($start_time) > strtotime($end_time)) {
            throw new Exception('开始时间不能大于结束时间');
        }
        if (strtotime($start_time) == strtotime($end_time)) {
            throw new Exception('开始时间不能等于结束时间');
        }
        //如果没有其他数据炬直接返回true
        if (empty($array)) {
            return true;
        } else {
            if ($id) {//编辑时候剔除当前编辑的数据，在进行处理
                foreach ($array as $k1 => $v1) {
                    if (in_array($id, $v1)) {
                        unset($array[$k1]);
                    }
                }
            }
        }
        if (empty($array)) {
            return true;
        }

        //首先进行时间区块排序，然后进行进行区块比较
        $need = [];
        foreach ($array as $key => &$value) {//转成时间，防止其他php版本出错
            $need[] = $value['start_time'] = strtotime($value['start_time']);
            $need[] = $value['end_time'] = strtotime($value['end_time']);
        }
        //先冒泡排序，在校验进入数组和原数组，防止数据错误
        $bubble_sort_result = self::bubbleSort($need);
        foreach ($array as $k => $v) {
            //数据校验,把原数组的start_time，end_time进行查找如果，如果2个键名的key相等或者相差大于1就是数据出错

            if (!in_array($v['start_time'], $bubble_sort_result)) {
                throw new Exception('数据校验出错，没有检测到开始时间');
            }
            if (!in_array($v['end_time'], $bubble_sort_result)) {
                throw new Exception('数据校验出错，没有检测到结束时间');
            }
            $start = array_search($v['start_time'], $bubble_sort_result);
            $end = array_search($v['end_time'], $bubble_sort_result);

            if ($start == $end) {
                throw new Exception('数据校验出错，开始时间和结束时间相同');
            }
            if ($end - $start > 1) {
                throw new Exception('数据校验出错，时间排序出错');
            }
        }
        //首先进行边界判断，在循环判断
        if (strtotime($start_time) < $bubble_sort_result['0'] && strtotime($end_time) < $bubble_sort_result['0']) {//结束时间必须小于对比数组第一个元素,否侧冲突
            return true;
        }
        if (strtotime($start_time) > $bubble_sort_result[(count($bubble_sort_result) - 1)] && strtotime($end_time) > $bubble_sort_result[(count($bubble_sort_result) - 1)]) {
            return true;
        }
        foreach ($bubble_sort_result as $kk => $vv) {
            //其他中间元素必须在01   23  45  67  89中间
            if ($kk % 2 == 1) {
                if (strtotime($start_time) > $bubble_sort_result[$kk] && strtotime($end_time) < $bubble_sort_result[$kk + 1]) {
                    return true;
                }
            }
        }
        throw new Exception('时间排序错误,请不要输入与他活动开始或结束活动相同的时间');
    }


    public static function bubbleSort(array $arr = [])
    {
        $len = count($arr);
        //设置一个空数组 用来接收冒出来的泡
        //该层循环控制 需要冒泡的轮数
        for ($i = 1; $i < $len; $i++) {
            //该层循环用来控制每轮 冒出一个数 需要比较的次数
            for ($k = 0; $k < $len - $i; $k++) {
                if ($arr[$k] > $arr[$k + 1]) {
                    $tmp = $arr[$k + 1];
                    $arr[$k + 1] = $arr[$k];
                    $arr[$k] = $tmp;
                }
            }
        }
        return $arr;
    }

}