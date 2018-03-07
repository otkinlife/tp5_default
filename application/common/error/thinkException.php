<?php
/**
 * @desc 异常处理类
 * @author kcjia
 * @time 2018/2/1
 */
namespace app\common\error;

use Exception;
use think\Config;
use think\exception\Handle;
use think\Log;

class thinkException extends Handle
{
    public function render(Exception $e)
    {
        if (Config::get('app_debug') === false) {
            //采集错误信息日志
            Log::error("错误详情--[]\r\n" .$e->getTraceAsString());
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-type: application/json');
            $email = config('administrator_mail');
            $errorStr = sprintf(
                '{"code": 500, "message": "'. Config::get('error_message').'"}',
                $email
            );
            echo $errorStr;
            die;
        } else {
            return parent::render($e);
        }
    }

}