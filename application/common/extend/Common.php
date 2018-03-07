<?php
/**
 * @desc service基类.
 * @author: kcjia
 * @time 2018/1/30
 */
namespace app\common\extend;

class Common
{
    //错误码
    private $rCode = 0;
    //错误信息
    private $rMessage = '';

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
}