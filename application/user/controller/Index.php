<?php
namespace app\user\controller;

use app\common\controller\BaseController;

class Index extends BaseController
{

    function __construct()
    {
        $this->service = service('User');
    }

    public function lists()
    {
        $userInfo = $this->service->getUserList();
        return $this->returnR($userInfo);
    }

    public function info()
    {
        $uid = I('uid', 0, 'int');
        $userInfo = $this->service->getUserInfoById($uid);
        return $this->returnR($userInfo);
    }

    public function check()
    {
        $account = I('post.account');
        $pwd = I('post.password');
        $type = I('type');
        $userInfo = $this->service->checkUserPwd($account, $pwd, $type);
        return $this->returnR($userInfo);
    }
}    