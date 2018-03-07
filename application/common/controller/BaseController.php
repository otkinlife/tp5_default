<?php
/**
 * @desc PhpStorm.
 * @author: kcjia
 * @time 2018/2/2
 */

namespace app\common\controller;


use think\Controller;

class BaseController extends Controller
{
    protected $service;

    /**
     * @desc 结果统一处理方法
     * @param $data array 返回数据
     * @return \think\response\Json
     * @author kcjia
     * @time 2018/2/27
     */
    protected function returnR($data, $code = '')
    {
        if (is_numeric($code)) {
            $result['code'] = $code;
        } else {
            $result['code'] = $this->service->getRCode();
        }

        if ($result['code'] === E_OK) {
            $result['data'] = $data;
        } else {
            $result['message'] = $this->service->getRMessage();
        }
        return json($result);
    }

}