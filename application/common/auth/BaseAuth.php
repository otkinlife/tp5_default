<?php
/**
 * @desc 校验相关基类
 * @author kcjia
 * @time 2018/1/25
 */
namespace app\common\auth;
use think\Cookie;
use think\Session;

class BaseAuth
{
    protected $error = '';
    protected $errorCode = 0;

    /**
     * @desc 获取当前用户信息
     * @return array|mixed
     * @author kcjia
     * @time 2018/1/25
     */
    public static function getCurrentUser()
    {
        $token = Cookie::get('user_key');
        if (empty($token)) {
            return [];
        }
        $user = Session::get($token);
        return $user;
    }

    /**
     * @desc 设置当前用户信息
     * @param $user
     * @return bool
     * @author kcjia
     * @time 2018/1/25
     */
    public function setCurrentUser($user)
    {
        try {
            if (empty($user)) {
                return false;
            }
            $data  = array(
                'id'       => $user['id'],
                'nickname' => $user['email'],
                'time'     => time()
            );
            $token = encrypt($data, 'E');
            Cookie::set('user_key', $token, 3600 * 24);
            Session::set($token, $user);
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * @desc 清除当前用户信息
     * @return bool
     * @author kcjia
     * @time 2018/1/25
     */
    public function delCurrentUser()
    {
        try {
            $token = Cookie::get('user_key');
            Session::delete($token);
            Cookie::delete('user_key');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @desc 判断用户是否登录
     * @return bool
     * @author kcjia
     * @time 2018/1/25
     */
    public function isLogin()
    {
        $token = Cookie::get('user_key');
        if (empty($token)) {
            return false;
        }
        $res = Session::has($token);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * @desc 登录校验+权限校验
     * @return bool
     */
    public function checkOptionPermission()
    {
        $isLogin = $this->isLogin();
        if (!$isLogin) {
            return E_ACCESS;
        }
        $user = $this->getCurrentUser();
        $res = service('User')->checkUserPermission($user['id']);
        return $res;
    }


    /**
     * @desc 校验预览模式
     * @note 返回值bool 代表是否是预览模式, 具体信息要看errCode
     * @return \think\response\Json
     */
    public function checkPreView($token = '')
    {
        if (empty($token)) {
            $token = Cookie::get('preViewKey');
            if (empty($token)) {
                $this->errorCode = 1;
                return false;
            }
            if (!Session::has($token)) {
                $this->errorCode = 1;
                return false;
            }
        }
        $token = str_replace('{[s]}', '+', $token);
        //解码
        $data = encrypt($token, 'D');
        $currentTime = time();
        if (!isset($data['mode'])
            || $data['mode'] != 'read'
        ) {
            $this->errorCode = 2;
            $this->error = 'token校验失败';
            return $token;
        }
        if (!isset($data['time'])
            || ($currentTime - $data['time']) > 60
        ) {
            $this->errorCode = 3;
            $this->error = '二维码已过期，请手动刷新后台预览二维码';
            return $token;
        }
        return $token;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getECode()
    {
        return $this->errorCode;
    }
}