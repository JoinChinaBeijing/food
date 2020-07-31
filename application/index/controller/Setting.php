<?php
namespace app\index\controller;
use think\Db;
use think\Config;
use think\Request;
use app\common\model\DateTimeTool;
use app\common\model\CacheTool;

class Setting extends Base
{
    public function index()
    {
        $setting = Db::table('setting')->select();

        $settingConfig = [
            'print' => '打印分单设置',
            'backpsd' => '退款设置密码'
        ];

        foreach ($setting as $key => $value)
        {
            $setting[$key]['statusDesc']   = $value['status'] == 0 ? '未开启' : '开启';
            $setting[$key]['names']        = isset($settingConfig[$value['name']]) ? $settingConfig[$value['name']] : '未定义';
        }

        $this->assign( "setting", json_encode($setting));
        $this->assign( "settingConfig", $settingConfig);
        return $this->fetch();
    }

    public function add()
    {
        $data = Request()->post();

        if (isset($data['id']) && !empty($data['id']))
        {
            $data['name'] = Db::table('setting')->where('id', $data['id'])->value('name');
        }

        // 加密
        if (isset($data['name']) && $data['name'] == 'backpsd')
        {
            $data['desc'] = md5($data['desc'] . Config::get('paystr'));
        }

        $data['createtime'] = date('Y-m-d H:i:s');
        try
        {
            if (isset($data['id']) && !empty($data['id']))
            {
                Db::table('setting')->update($data);
            }
            else
            {
                Db::table('setting')->insert($data);
            }
        }
        catch (\Exception $e)
        {
            echo json_encode(array('code' => 403, 'message' => $e->getMessage()));
            exit;
        }

        // 修改添加成功删除reids里的print
        if (isset($data['name']) && $data['name'] == 'print')
        {
            CacheTool::rmCache('redis', 'print');
        }

        echo json_encode(array('code' => 200, 'message' => '提交成功'));exit;
    }
}