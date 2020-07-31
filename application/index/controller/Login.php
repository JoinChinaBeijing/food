<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\UserModel;
use think\Request;
use think\Cache;
use gmars\rbac\Rbac;

/**
 * Class Login   登录页面
 * @package app\index\controller
 */
class Login extends Controller
{
    /**
     * 登录信息的验证
     */
    public function login()
    {
        if($_POST)
        {
            //获取登录数
            $data = Request()->post();

            // 判断是否成功登录
            $model = new UserModel();
            $result = $model->checkLogin($data);

            if ($result['code'] == 200)
            {
                $user = $result['data'];
                session('name', $user['name']);
                session("user", $user);

                if ($user['name'] != 'lt&qgy')
                {
                    $rbac = new Rbac();
                    $rbac->cachePermission($user['id']);
                }
                $this->success('登录成功', 'index/index');
            }
            else
            {
                $this->error($result['message']);
            }
        }

        return view('login');
    }


    /**
     * 退出
     */
    public function logout()
    {
        session(null);
        Cache::clear();
        $this->success('退出成功', 'login/login');
    }
}