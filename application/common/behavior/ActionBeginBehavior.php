<?php
/**
 * @desc  PhpStorm.
 * @author: jiakaichao
 * Time: 2017/6/28 14:16
 */
namespace app\common\behavior;

use app\common\auth\AdminAuth;
use app\common\auth\ApiAuth;
use app\common\auth\AuthAdapter;
use think\Config;
use think\Hook;
use think\Log;
use think\Request;
use think\Response;
use think\Cookie;
use think\Session;

class ActionBeginBehavior
{
    /**
     * 方法执行之前进行校验(检查是否登录和是否有权限)
     * @return bool
     */
    public function run()
    {

    }

    /**
     * 登录校验+权限校验
     * @return bool
     */
    private function checkPermission()
    {

    }

    /**
     * 该方法只用于此类
     * @param $data
     * @param int $code
     */
    private function printJson($code = E_OK, $data = '')
    {
        header('Content-Type: application/json');
        $apiCode = Config('api_code');
        $res = array(
            'code' => $code,
            'success' => $code === 0 ? true : false,
            'message' => isset($data) ? $data : $apiCode[$code]
        );
        echo json_encode($res);
    }

    /**
     * @desc 预览模式下对相应的接口进行转发
     */
    private function preViewMode($auth)
    {
        
    }
}