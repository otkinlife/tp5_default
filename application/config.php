<?php
/**
 * @desc 配置文件入口
 * @author kcjia
 * @time 2018/1/25
 */
$config = require(__DIR__ . '/config/config.php');
$module = require (__DIR__. '/config/module.php');
$config['module'] = $module;
return $config;