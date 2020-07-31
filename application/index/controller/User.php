<?php
namespace app\index\controller;

use think\Db;
use think\Config;
use think\Request;
use app\common\model\DateTimeTool;
use app\index\model\UserModel;

class User extends Base
{
    public function index()
    {
        $model    = new UserModel();
        $userList = $model->getUsetList();

        $roleList  = $model->getRoleList();
        $storeList = $model->getStoreList();

        $this->assign("userList", json_encode($userList));
        $this->assign( "roleList", $roleList);
        $this->assign( "storeList", $storeList);
        return $this->fetch();
    }

    public function add()
    {
        $data = Request()->post();

        $model  = new UserModel();
        $result = $model->saveUser($data);

        echo json_encode($result);exit;
    }
}
