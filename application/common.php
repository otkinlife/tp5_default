<?php

/**
 * @des 调试打印方法
 * @param mixed $data 要打印的数据
 * @param bool $die
 * @author kcjia
 * @time 2018/1/25
 */
function P($data, $die = false)
{
    dump($data);
    if ($die) {
        die;
    }
}

/**
 * @desc 设置/读取/删除缓存
 * @param string $key 缓存的key
 * @param string $value 缓存数据
 * @param int $expire 过期时间
 * @param string $type 缓存驱动类型(目前支持default(file)/redis)
 * @return bool|mixed
 * @author kcjia
 * @time 2018/1/25
 */
function S($key, $value = '', $expire = null, $type = 'default')
{
    if ($value === '') {
        return think\Cache::store($type)->get($key);
    }

    if (is_null($value)) {
        return think\Cache::store($type)->rm($key);
    }

    if (!$expire) {
        $expire = config('cache');
        $expire = $expire[$type]['expire'];
    }
    return think\Cache::store($type)->set($key, $value, $expire);

}

/**
 * @desc 获取输入参数 支持过滤和默认值
 * @note 使用方法: I('id',0); 获取id参数 自动判断get或者post
 * @note 使用方法: I('post.name','',''); 获取$_POST['name']
 * @note 使用方法: I('get.'); 获取$_GET
 * @param string $name 变量的名称 支持指定类型
 * @param mixed $default 不存在的时候默认值
 * @param mixed $filter 参数过滤方法
 * @param mixed $sourceData 要获取的额外数据源
 * @return mixed
 * @author kcjia
 * @time 2018/1/25
 */
function I($name, $default = '', $filter = null)
{
    //过滤
    think\Request::instance()->filter('htmlspecialchars');
    return input($name, $default, $filter);
}

/**
 * @desc 获取service
 * @param $serviceName
 * @return \think\Model
 * @author kcjia
 * @time 2018/2/2
 */
function service($serviceName, $module = '')
{
    if (empty($module)) {
        $module = \think\Request::instance()->module();
    }
    return \think\Loader::model($serviceName, 'service', false, $module);
}

