<?php
namespace app\index\controller;

use think\Db;
use think\Config;
use think\Request;
use app\common\model\DateTimeTool;

class Permission extends Base
{
    public function index()
    {
        $permission = $this->rbac->getPermission('');

        $permissionCategory = Config::get('permission');
        foreach ($permission as $key => $value)
        {
            $permission[$key]['statusDesc']   = $value['status'] == 0 ? '未开启' : '开启';
            $permission[$key]['categoryName'] = isset($permissionCategory[$value['category_id']]) ? $permissionCategory[$value['category_id']] : '';
        }

        $this->assign( "permission", json_encode($permission));
        $this->assign( "permissionCategory", $permissionCategory);
        return $this->fetch();
    }

    public function add()
    {
        $data = Request()->post();
        if (isset($data['id']) && !empty($data['id']))
        {
            $result = $this->rbac->editPermission($data, $data['id']);
        }
        else
        {
            $data['create_time'] = DateTimeTool::getDate('Y-m-d H:i:s');
            $result = $this->rbac->createPermission($data);
        }

        echo json_encode($result);exit;
    }

    public function del()
    {
        $id = Request()->post()['id'];
        $result = $this->rbac->delPermission($id);

        echo json_encode($result);exit;
    }
}
