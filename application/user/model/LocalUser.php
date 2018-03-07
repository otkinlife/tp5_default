<?php
namespace app\user\model;

use app\common\helper\ArrayHelper;
use app\common\model\BaseModel;
use think\Db;

class LocalUser extends BaseModel
{
    /**
     * @desc 根据用户id获取用户信息
     * @param $uid
     * @return array|false|\PDOStatement|string|\think\Collection
     * @author kcjia
     * @time 2018/2/27
     */
    public function getUserById($uid)
    {
        $condition = [
            'I.id' => $uid
        ];
        $userInfo = $this->getUserByCondition($condition);
        if (empty($userInfo)) {
            return [];
        }
        return $userInfo[0];
    }

    /**
     * @desc 获取用户列表
     * @return array|mixed
     * @author kcjia
     * @time 2018/2/28
     */
    public function getUserList()
    {
        $userInfo = $this->getUserByCondition();
        if (empty($userInfo)) {
            return [];
        }
        return $userInfo;
    }

    /**
     * @desc 校验用户密码
     * @note phone 手机号的传入方式 +86_12343423431
     * @param $account string 用户账户(手机号/邮箱)
     * @param $pwd string 用户密码
     * @param $type string 用户校验类型
     * @return array|mixed
     * @author kcjia
     * @time 2018/3/1
     */
    public function checkPwd($account, $pwd, $type = 'phone')
    {
        $condition['password'] = $pwd;
        if ($type == 'phone') {
            $arr = explode('_', $account);
            $condition['area_code'] = $arr[0];
            $condition['phone'] = $arr[1];
        } else {
            $condition['email'] = $account;
        }
        $userInfo = $this->getUserByCondition($condition);
        if (empty($userInfo)) {
            return [];
        }
        return $userInfo[0];
    }

    public function addUser($data)
    {
        $time = time();
        $data['create_time'] = $time;
        $data['update_time'] = $time;
        $field = [
            'area_code',
            'phone',
            'email',
            'sex',
            'nickname',
            'structure_id',
            'station_id',
            'create_time',
            'update_time'
        ];
        $localData = ArrayHelper::parts($data, $field);
        $uid = Db::name('user_local')->insertGetId($localData);
        $roleId = isset($data['role_id']) ? $data['role_id'] : 1;
        $indexData = [
            'type' => 'local',
            'key' => $uid,
            'role_id' => $roleId,
            'create_time' => $time,
            'update_time' => $time
        ];
        $index_id = Db::name('user_index')->insertGetId($indexData);
    }

    /**
     * @desc 根据条件获取某个用户信息
     * @param $condition array 查询条件
     * @return array|false|\PDOStatement|string|\think\Collection
     * @author kcjia
     * @time 2018/2/28
     */
    public function getUserByCondition($condition = [])
    {
        $res = [];
        $condition['I.type'] = 'local';
        $filed = 'I.id index_id, L.*, S.name structure, St.name station';
        $userInfo = Db::name('user_index')
            ->field($filed)
            ->alias('I')
            ->join('user_local L', 'I.key=L.id', 'left')
            ->join('user_structure S', 'L.structure_id=S.id', 'left')
            ->join('user_station St', 'L.station_id=St.id', 'left')
            ->where($condition)
            ->select();
        if (empty($userInfo)) {
            return [];
        }
        foreach ($userInfo as $item) {
            $res[] = $this->formatUserInfo($item);
        }
        return $res;
    }

    /**
     * @desc 格式化用户信息
     * @param $user
     * @return array
     * @author kcjia
     * @time 2018/2/27
     */
    protected function formatUserInfo($user)
    {
        $filter = [
            'index_id',
            'id',
            'email',
            'sex',
            'nickname',
            'small_avatar',
            'medium_avatar',
            'large_avatar',
            'structure',
            'station'
        ];
        if (isset($user['area_code']) && isset($user['phone'])) {
            $user['phone'] = $user['area_code'] . $user['phone'];
            $filter[] = 'phone';
        }
        $res = ArrayHelper::parts($user, $filter);
        return $res;
    }
}