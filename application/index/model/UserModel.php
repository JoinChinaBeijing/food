<?php

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\UserValidate;
use gmars\rbac\Rbac;
use think\Config;
use think\Db;

class UserModel extends CommonModel
{

    protected $table    = 'user';

    /**
     * 获取用户列表
     * @param $code //城市代码
     *
     * @return string
     */
    public function getUsetList()
    {
        $userList = Db::name('user')->alias('u')
                         ->join('user_role ur', 'u.id = ur.user_id', 'LEFT')
                         ->join('role r', 'r.id = ur.role_id', 'LEFT')
                         ->join('store s', 's.id = u.sid', 'LEFT')
                         ->field('u.id,u.name,if(u.status = 1, "正常", "禁用") as statusDesc,u.status,u.mobile,u.last_login_time,u.create_time,u.create_by, r.name as rolename, r.id as role_id, s.name as storeName, u.sid')
                         ->select();

        return $userList;
    }

    /**
     * 获取角色键值对
     *
     * @return array
     */
    public function getRoleList()
    {
        return Db::name('role')->where('status = 1')->column('name', 'id');
    }

    public function getStoreList()
    {
        return Db::name('store')->where('status', '1')->column('name', 'id');
    }

    /**
     * 添加和修改管理员
     *
     * @param array $data //提交数据
     *
     * @return string
     */
    public function saveUser($data)
    {
        $city = new UserValidate();
        $result = $city->check( $data );

        if ($result == false)
        {
            return array( 'code' => "403", 'message' => $city->getError());
        }

        if (isset($data['id']) && !empty($data['id']))
        {
            if (!empty($data['password']))
            {
                $data['password'] = md5($data['password'] . Config::get('token'));
            }
            else
            {
                unset($data['password']);
            }

            $data['update_time'] = DateTimeTool::getDate('Y-m-d H:i:s');
            $result = $this->createUser($data);
        }
        else
        {
            if (empty($data['password']))
            {
                return array('code' => 403, 'message' => '密码不能为空');
            }

            $data['password']    = md5($data['password'] . Config::get('token'));
            $data['create_time'] = DateTimeTool::getDate('Y-m-d H:i:s');
            $data['create_by']   = 'lt&qgy';
            $result = $this->createUser($data);
        }

        return $result;
    }

    /**
     * 添加和修改管理员
     *
     * @param array $data //提交数据
     *
     * @return string
     */
    public function createUser($data)
    {
        try{
            $roleID = $data['role_id'];
            unset($data['role_id']);
            if (isset($data['id']) && !empty($data['id']))
            {
                $this->save($data, ['id' => $data['id']]);
                $result = array('code' => 200, 'message' => '修改成功');
            }
            else
            {
                $this->save($data);
                $result = array('code' => 200, 'message' => '添加成功');
            }

            $rbac = new Rbac();
            $rbac->assignUserRole($this->id, [$roleID]);
            return $result;
        } catch (Exception $e) {
            return array('code' => 403, 'message' => $e->getMessage());
        }
    }

    /**
     * 登陆验证
     *
     * @param array $data //提交数据
     *
     * @return string
     */
    public function checkLogin($data)
    {
        if (empty($data['name']))
        {
            return array('code' => 402, 'message' => '名称或电话不能为空');
        }

        if (empty($data['password']))
        {
            return array('code' => 402, 'message' => '密码不能为空');
        }

        $user = Db::table('user')->where('name|mobile', '=', $data['name'])
                     ->find();

        if (empty($user))
        {
            return array('code' => 402, 'message' => '账号或电话不对');
        }

        if ($user['password'] == md5($data['password'] . Config::get('token')))
        {
            unset($user['password']);
            $this->save(['last_login_time' => DateTimeTool::getDate('Y-m-d H:i:s')], ['id' => $user['id']]);
            return array('code' => 200, 'message' => '登录成功', 'data' => $user);
        }
        else
        {
            return array('code' => 402, 'message' => '密码错误');
        }
    }
}