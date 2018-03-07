<?php
/**
 * @desc model基类.
 * @author: kcjia
 * @time 2018/2/2
 */

namespace app\common\model;

use think\Db;
use think\Model;

class BaseModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = $this->db();
    }

    //错误码
    private $rCode = E_OK;
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
}