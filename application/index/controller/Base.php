<?php
namespace app\index\controller;

use think\Controller;
use gmars\rbac\Rbac;
use think\Exception;
use think\session;

class Base extends Controller
{
    protected $rbac;
    public function __construct()
    {
        parent::__construct();
        $this->rbac = new Rbac();
        $user = session('user');
        // 验证是否登陆
        if (isset($user['name']) && !empty($user['name']))
        {
            // 验证权限
            $model      = strtolower(request()->module());
            $controller = strtolower(request()->controller());
            $action     = strtolower(request()->action());
            $path = $model . '/' . $controller . '/' . $action;

            try
            {
                if (!$this->rbac->can($path))
                {
                    // echo $this->alert_error('你的账号暂无对应权限，请联系管理员开通');
                    // exit;
                    // return array('code' => '401', 'message' => '你的账号暂无对应权限，请联系管理员开通');
                    // $this->error('你的账号暂无对应权限，请联系管理员开通');
                    if ($_POST)
                    {
                        echo json_encode(array('code' => 401 , 'message' => '你的账号暂无对应权限，请联系管理员开通'));
                        exit;
                    }
                    else
                    {
                        echo "<script type='text/javascript'>alert('你的账号暂无对应权限，请联系管理员开通');</script>";
                        exit;
                    }
                }
            }
            catch (Exception $e)
            {
                // echo $e->getMessage();
                // exit;
                // $this->error($e->getMessage());
                echo "<script type='text/javascript'>alert({$e->getMessage()});</script>";exit;
                // return array('code' => '403', 'message' => $e->getMessage());
            } 
        }
        else
        {
            $this->redirect('index/login/login');
        }
    }
}