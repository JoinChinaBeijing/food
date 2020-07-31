<?php
namespace app\index\controller;
use think\Db;
use think\Config;
use think\Request;
use app\common\model\DateTimeTool;

class Role extends Base
{
    public function index()
    {
        $role = $this->rbac->getRole('');
        $permission = $this->rbac->getPermission('status = 1');

        $permissionCategory = Config::get('permission');
        foreach ($role as $key => $value)
        {
            $role[$key]['statusDesc']   = $value['status'] == 0 ? '未开启' : '开启';
        }

        $this->assign( "role", json_encode($role));
        $this->assign( "permissionCategory", $permissionCategory);
        $this->assign( "permission", $permission);
        return $this->fetch();
    }

    public function add()
    {
        $data = Request()->post();
        $permissionIds = $data['permissionIds'];
        unset($data['permissionIds']);

        $result = $this->rbac->createRole($data, $permissionIds);

        echo json_encode($result);
    }

    public function del()
    {
        $id = Request()->post()['id'];
        $result = $this->rbac->delRole($id);

        echo json_encode($result);exit;
    }

}
