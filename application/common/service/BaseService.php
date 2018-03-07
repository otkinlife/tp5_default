<?php
/**
 * @desc service基类.
 * @author: kcjia
 * @time 2018/1/30
 */
namespace app\common\service;

use think\console\command\make\Model;

class BaseService extends Model
{
    //错误码
    private $rCode = E_OK;
    //错误信息
    private $rMessage = '';

    protected $model;

    /**
     * @desc 设置返回码
     * @param int $code
     * @author kcjia
     * @time 2018/1/31
     */
    public function setRCode($code)
    {
        $this->rCode = $code;
    }

    /**
     * @desc 设置返回信息
     * @return int
     * @author kcjia
     * @time 2018/1/31
     */
    public function getRCode()
    {
        return $this->rCode;
    }

    /**
     * @desc 设置返回信息
     * @param string $message
     * @author kcjia
     * @time 2018/1/31
     */
    public function setRMessage($message)
    {
        $this->rMessage = $message;
    }

    /**
     * @desc 获取返回信息
     * @return string
     * @author kcjia
     * @time 2018/1/31
     */
    public function getRMessage()
    {
        return $this->rMessage;
    }

    /**
     * @desc 设置返回信息
     * @param $code
     * @param $message
     * @author kcjia
     * @time 2018/2/28
     */
    public function setR($message, $code)
    {
        $this->rCode = $code;
        $this->rMessage = $message;
    }

    /**
     * @desc 将model层的信息传给本层
     * @author kcjia
     * @time 2018/2/27
     */
    public function setModelR()
    {
        if (!isset($this->rCode)) {
            $rCode = $this->model->getRCode();
            $this->rCode = empty($rCode) ? 0 : $rCode;
        }
        if (!isset($this->rMessage)) {
            $rMessage = $this->model->getRMessage();
            $this->rMessage = empty($rMessage) ? '' : $rMessage;
        }
    }
}