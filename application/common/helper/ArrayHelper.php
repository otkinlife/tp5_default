<?php
/**
 * @desc 数组相关工具类
 * @author kcjia
 * @time 2018/1/25
 */
namespace app\common\helper;

class ArrayHelper
{
    /**
     * @desc 获取数组的某个元素的值
     * @param array $array 需要的数组
     * @param mixed $key 需要获取的值的key
     * @param mixed $default 如果没有改元素的默认值
     * @return mixed
     * @author kcjia
     * @time 2018/1/25
     */
    public static function get(array $array, $key, $default)
    {
        if (isset($array[$key])) {
            return $array[$key];
        } else {
            return $default;
        }
    }

    /**
     * @desc 获取数组的某一列
     * @param array $array
     * @param $columnName
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function column(array $array, $columnName)
    {
        if (function_exists('array_column')) {
            return array_column($array, $columnName);
        }

        if (empty($array)) {
            return array();
        }

        $column = array();

        foreach ($array as $item) {
            if (isset($item[$columnName])) {
                $column[] = $item[$columnName];
            }
        }

        return $column;
    }

    /**
     * @desc 过滤数组的数据
     * @param array $array 需要过滤的数组
     * @param array $keys 要保留的数据
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function parts(array $array, array $keys)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * @desc 检查数组的元素是否存在
     * @param array $array 需要被检查的数组
     * @param array $keys 要检查的key
     * @param bool $strictMode 是否检查key的空值情况
     * @return bool
     * @author kcjia
     * @time 2018/1/25
     */
    public static function required(array $array, array $keys, $strictMode = false)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
            if ($strictMode && (is_null($array[$key]) || $array[$key] === '' || $array[$key] === 0)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @desc 检查两个数组的差异
     * @param array $before
     * @param array $after
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function changes(array $before, array $after)
    {
        $changes = array('before' => array(), 'after' => array());

        foreach ($after as $key => $value) {
            if (!isset($before[$key])) {
                continue;
            }

            if ($value != $before[$key]) {
                $changes['before'][$key] = $before[$key];
                $changes['after'][$key] = $value;
            }
        }

        return $changes;
    }

    /**
     * @desc 将数组按照key分组
     * @param array $array
     * @param $key
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function group(array $array, $key)
    {
        $grouped = array();

        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = array();
            }

            $grouped[$item[$key]][] = $item;
        }

        return $grouped;
    }

    /**
     * @desc 给数组的key重新命名
     * @param array $array
     * @param array $map
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function rename(array $array, array $map)
    {
        $keys = array_keys($map);

        foreach ($array as $key => $value) {
            if (in_array($key, $keys)) {
                $array[$map[$key]] = $value;
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * @desc 过滤数组数据
     * @param array $array
     * @param array $specialValues
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function filter(array $array, array $specialValues)
    {
        $filtered = array();

        foreach ($specialValues as $key => $value) {
            if (!array_key_exists($key, $array)) {
                continue;
            }

            if (is_array($value)) {
                $filtered[$key] = (array)$array[$key];
            } elseif (is_int($value)) {
                $filtered[$key] = (int)$array[$key];
            } elseif (is_float($value)) {
                $filtered[$key] = (float)$array[$key];
            } elseif (is_bool($value)) {
                $filtered[$key] = (bool)$array[$key];
            } else {
                $filtered[$key] = (string)$array[$key];
            }

            if (empty($filtered[$key])) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * @desc 清除数组数据的格式
     * @param $array
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function trim($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::trim($value);
            } elseif (is_string($value)) {
                $array[$key] = trim($value);
            }
        }

        return $array;
    }

    /**
     * @desc 二维数组合并值，返回去除重复值的一维数组.
     * @param $doubleArrays
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function mergeArraysValue($doubleArrays)
    {
        $values = array();
        foreach ($doubleArrays as $array) {
            if (empty($array)) {
                continue;
            }
            foreach ($array as $value) {
                if (in_array($value, $values)) {
                    continue;
                }
                $values[] = $value;
            }
        }
        return $values;
    }

    /**
     * @desc 将json转为array
     * @param string $jsonStr 要转换的字符串
     * @return array|mixed
     * @author kcjia
     * @time 2018/1/25
     */
    public static function toArray($jsonStr)
    {
        if (is_array($jsonStr)) {
            return $jsonStr;
        }
        $array = json_decode($jsonStr, true);
        $array = is_array($array) ? $array : array();
        return $array;
    }

    /**
     * @desc 将数组的key换成某一列
     * @param $data
     * @param $key
     * @return array
     * @author kcjia
     * @time 2018/1/25
     */
    public static function toKeyValue($data, $key)
    {
        $array = array();
        foreach ($data as $k => $v) {
            $array[$v[$key]] = $v;
        }
        return $array;
    }
}