<?php
/**
 * @desc 这里定义一些系统的常量.
 * @author: kcjia
 * @time 2018/1/25
 */

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//ENV代表代码的环境(线上,测试,开发),SYS代表系统(im,bl,es)等,主要用于获取连接的数据库
$env = empty($_SERVER['APP_ENV']) ? 'dev' : $_SERVER['APP_ENV'];
switch ($env) {
    case 'es':
    case 'im':
    case 'bl':
    case 'cl':
    case 'an':
    case 'njy':
    case 'hl':
    case 'ab':
    case 'aa':
        if (!defined('_ENV_')) {
            define('_ENV_', 'prod');
        }
        break;
    case 'test':
        if (!defined('_ENV_')) {
            define('_ENV_', 'test');
        }
        break;
    case 'dev':
    default :
        if (!defined('_ENV_')) {
            define('_ENV_', 'dev');
        }
}

if (!defined('_SYS_')) {
    define('_SYS_', $env);
}
