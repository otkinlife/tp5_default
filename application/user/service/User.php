<?php
namespace app\user\service;

use app\common\helper\ArrayHelper;
use app\common\helper\BaseHelper;
use app\common\service\BaseService;

class User extends BaseService
{
    /**
     * @desc User constructor.
     * @note 构造函数,实例化本服务类对应的model
     * @note 建立model文件时按照规则 **User.php
     * @note 前缀在配置文件module.php里配置
     */
    function __construct()
    {
        $config = config('module.user');
        if (empty($config['type'])) {
            $this->model = model('LocalUser');
        }
        $modelName = ucfirst($config['type']) . 'User';
        $this->model = model($modelName);
    }

    /**
     * @desc 获取用户列表
     * @return mixed
     * @author kcjia
     * @time 2018/2/28
     */
    public function getUserList()
    {
        $userList = $this->model->getUserList();
        $this->setModelR();
        return $userList;
    }

    /**
     * @desc 校验用户密码
     * @param $userPwd string 用户密码
     * @param $account string 用户账户(手机号/邮箱)
     * @param string $type 校验类型(手机号/邮箱)
     * @return array|bool
     * @author kcjia
     * @time 2018/3/1
     */
    public function checkUserPwd($userPwd, $account, $type = 'phone')
    {
        $userInfo = [];
        $userPwd = BaseHelper::encrypt($userPwd, 'E');
        if (empty($account)) {
            $this->setR('账户不能为空', E_LOGIN);
            return false;
        }
        if ($type == 'email') {
            $userInfo = $this->model->checkPwd($account, $userPwd, 'email');
        }
        if ($type == 'phone') {
            $userInfo = $this->model->checkPwd($account, $userPwd);
        }
        if (empty($userInfo)) {
            $this->setR('用户名或密码错误', E_LOGIN);
            return false;
        }
        return $userInfo;
    }

    /**
     * @desc 根据uid获取用户信息
     * @param $uid int 用户uid
     * @return mixed
     * @author kcjia
     * @time 2018/2/27
     */
    public function getUserInfoById($uid)
    {
        if (empty($uid)) {
            $this->setR('用户id不能为空', E_PARAMS);
            return false;
        }
        $userInfo = $this->model->getUserById($uid);
        $this->setModelR();
        return $userInfo;
    }

    public function addUser()
    {

    }

    public function updateUser()
    {

    }

    public function deleteUser()
    {

    }

}    