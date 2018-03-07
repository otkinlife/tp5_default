<?php
/**
 * @desc 错误码.
 * @author: kcjia
 * @time 2018/1/25
 */

define('E_OK', 0);
define('E_PARAMS', 1001);
define('E_FAIL', 1002);
define('E_TIME', 1003);
define('E_ACCESS', 1004);
define('E_PERMISSION', 1005);
define('E_CONFIRM', 1006);
define('E_DELETED_COURSE', 1007);
define('E_DELETED_TEST_PAPER', 1008);
define('E_DELETED_USER', 1009);
define('E_DELETED_USER_GROUP', 1010);
define('E_LOGIN', 1011);


$GLOBALS['E_MESSAGE'] = [
    E_OK => '请求成功',
    E_PARAMS => '参数错误',
    E_FAIL => '请求失败',
    E_TIME => '请求超时,请重试',
    E_ACCESS => 'AccessToken校验失败',
    E_PERMISSION => '抱歉,权限不够',
    E_CONFIRM => '确认提醒',
    E_DELETED_COURSE => '课程已删除或不存在',
    E_DELETED_TEST_PAPER => '试卷已删除',
    E_DELETED_USER => '用户已删除',
    E_DELETED_USER_GROUP => '用户组已删除',
    E_LOGIN => '用户登录失败'
];